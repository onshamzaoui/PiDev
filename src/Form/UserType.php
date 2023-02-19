<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Speciality;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email_user')
            ->add('nom_user')
            ->add('password_user',PasswordType::class)
            // ->add('role_user')
            ->add('adresse_user')
            // ->add('speciality')
            ->add('speciality', EntityType::class, [
                'class' => Speciality::class,
                'choice_label' => 'speciality_name',
            ])
            ->add('Save',SubmitType::class)
            ;
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
