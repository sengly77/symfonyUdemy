<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', TextType::class, [
                ])  // ['attr' => ['class' => 'form_model']] pour input , 'label_attr' => ['class' => 'form_model'],'required' => false pour mettre class="form_model" au label->ancienne method
            ->add('price', NumberType::class, [
                ]) // par ex: TextType::class pour mettre type text
            ->add('image', ImageType::class, ['label'=>false])
            ->add('keywords', CollectionType::class, [
                   'entry_type' => KeywordType::class,
                   'allow_add' => true,
                   'by_reference' => false
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);


    }
}
