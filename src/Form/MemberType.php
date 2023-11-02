<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Member;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo : ',
                'invalid_message' => 'Pseudo déjà utilisé, merci d\'en choisir un autre.',
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom : '
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom : '
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone : ',
                'required' => false
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email : '
            ])
             ->add('password', RepeatedType::class, [
                 'type' => PasswordType::class,
                 'invalid_message' => 'Les mots de passe ne correspondent pas, merci de réessayer.',
                 'options' => ['attr' => ['class' => 'password-field']],
                 'required' => true,
                 'first_options'  => ['label' => 'Mot de Passe : '],
                 'second_options' => ['label' => 'Confirmation : '],
             ])
            ->add('campus', EntityType::class, [
                'label'=> 'Campus : ',
                'class' => Campus::class,
                'choice_label' => 'name',
                'placeholder'=> '--Choisir un campus--'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
