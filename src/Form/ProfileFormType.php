<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //     ->add('email_user')
        //     ->add('nom_user')
        //     ->add('password_user')
        //     ->add('role_user')
        //     ->add('adresse_user')
            ->add('speciality')
        // ;
        ->add('nomUser')
        ->add('emailUser')
        ->add('plainPassword', PasswordType::class, [
            'mapped' => false,
            'required' => false,
            'attr' => ['autocomplete' => 'new-password'],
        ])
        ->add('adresseUser')
        ->add('points')

        

    ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
