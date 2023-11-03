<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\Excursion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security;

class ExcursionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom : '
            ])
            ->add('startDate', DateTimeType::class, [
                "label" => "Date de l'événement :",
                'widget' => 'single_text',
            ])
            ->add('duration', IntegerType::class, [
                "label" => "Durée :",
            ])

            ->add('limitRegistrationDate',DateTimeType::class, [
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text'
            ])
            ->add('maxRegistrationNumber', IntegerType::class, [
                'label' => 'Nombre maximum de participants'

            ])
            ->add('description', TextareaType::class, [
                'label' => 'Décrivez votre événement',
                'required' => false
             ])

            ->add('campus', EntityType::class, [
                'label'=> 'Campus',
                'class' => Campus::class,
                'choice_label' => 'name',
                'placeholder'=> '--Choisir un campus--'
            ])

            ->add('city', EntityType::class, [
                'label'=> 'Ville : ',
                'mapped'=> false,
                'class' => City::class,
                'choice_label' => 'name',
                'placeholder'=> '--Choisir une ville--'
            ])

            ->add('place', EntityType::class, [
                'label'=> 'Lieu : ',
                'class' => Place::class,
                'choice_label' => 'name',
                'placeholder'=> '--Choisir un lieu--'
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Excursion::class,
        ]);
    }
}
