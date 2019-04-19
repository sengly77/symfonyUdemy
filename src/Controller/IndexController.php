<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Image;
use App\Entity\Keyword;
use App\Form\CarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /*
     * @Route("/", name="home", methods={"get"})
     */
    /*
    public function hello(){
        $languages = [
          'php',
          'html',
          'javascript',
          'java'
        ];
        return $this->render('index.html.twig' , [
            'languages' => $languages
        ]);
    }
    */

    /*
     * @Route("/{language}", name="show")
     */
    /*
    public function index($language){
        return $this->render('show.html.twig', [
            'language_choisi' => $language,
        ]);
    }
    */

    /**
     * @Route("/", name="home")
     */
    public function index(CarRepository $carRepository){

        $cars = $carRepository->findAll();

        return $this->render('home/index.html.twig', [
            'cars' => $cars
        ]);
    }

    /**
     * @Route("/{language}", name="show")
     */
    /*
    public function show($language){
        return $this->render('home/show.html.twig', [
            'language_choisi' => $language,
        ]);
    }
    */
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(){
        return $this->render('home/contact.html.twig');
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(EntityManagerInterface $manager, Request $request){


        $form = $this->createForm(CarType::class);
        //$form = $this->createForm(CarType::class, null,['my_model' => 'clio', 'my_price' => '1900']); pour préremplir en dur

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $path = $this->getParameter('kernel.project_dir').'/public/images';
            //recupère les valeurs sous forme d'objet Car
            $car = $form->getData();


            //recupere l'image
            /** @var Image $image */
            $image = $car->getImage();

            //recupere le file soumis
            /** @var UploadedFile $file */
            $file = $image->getFile();

            //creer un nom unique
            $name = md5(uniqid()).'.'.$file->guessExtension();

            //deplace le fichier
            $file->move($path, $name);

            //donne le nom à l'image
            $image->setName($name);

            $manager->persist($car);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Super ! Une nouvelle voiture a été ajouté'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('home/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Car $car, EntityManagerInterface $manager, Request $request){

        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //$car = $form->getData();
            // $manager->persist($car); pas besoin car provient de la bdd
            $manager->flush();

            $this->addFlash(
                'notice',
                'Super ! Une nouvelle voiture est bien modifié'
            );

            return $this->redirectToRoute('home');
        }


        return $this->render('home/edit.html.twig', [
            'car' => $car,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Car $car, EntityManagerInterface $manager){

        $manager->remove($car);
        $manager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Car $car){
        //Car::class = Entity\Car
        //$car = $this->getDoctrine()->getRepository(Car::class)->find($id);
        return $this->render('home/show.html.twig', [
            'car' => $car
        ]);
    }

}