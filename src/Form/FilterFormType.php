<?php

namespace App\Form;

use App\Entity\Campus;
use App\Form\Model\FilterModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('selectedCampus', EntityType::class, [
                'label' => 'Campus ',
                'class' => Campus::class,
                'choice_label' => 'name',
                'placeholder' => '--Choisir un campus--',
                'required' => false
            ])
            ->add('selectedWords', TextType::class, [
                'label' => 'Le nom de la sortie contient',
                'required' => false
            ])
            ->add('selectedStartDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Entre',
                'required' => false
            ])
            ->add('selectedEndDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'et',
                'required' => false
            ])
            ->add('isOrganizer', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
            ->add('isRegistred', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false
            ])
            ->add('isNotRegistred', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false
            ])
            ->add('isFinished', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'data_class'=> FilterModel::class,
        ]);
    }
}
