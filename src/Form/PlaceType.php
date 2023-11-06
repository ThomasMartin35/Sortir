<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du lieu : '
            ])
            ->add('street', TextType::class, [
                'label' => 'Rue : '
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude : '
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude : '
            ])
            ->add('city', EntityType::class, [
                'label'=> 'Ville : ',
                'class' => City::class,
                'choice_label' => 'name',
                'placeholder'=> '--Choisir une ville--'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
