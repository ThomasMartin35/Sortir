<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Excursion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExcursionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('startDate', DateTimeType::class, [
                'date_label' => 'Commence le',
            ])
            ->add('duration', IntegerType::class, [
                ])
            ->add('limitRegistrationDate',DateTimeType::class, [
                'date_label' => 'Limite inscription',
            ])
            ->add('maxRegistrationNumber', IntegerType::class, [
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

            // TODO ->add('place')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Excursion::class,
        ]);
    }
}
