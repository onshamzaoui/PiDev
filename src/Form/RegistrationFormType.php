<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Speciality;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('email_user')
            //  ->add('agreeTerms', CheckboxType::class, [
            //      'mapped' => false,
            //  'constraints' => [
            //        new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //      ]),
            //    ],
            //  ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('emailUser')
            ->add('nomUser')
            // ->add('role_user')
            ->add('adresseUser')
            // ->add('speciality')
            ->add('speciality', EntityType::class, [
                'class' => Speciality::class,
                'choice_label' => 'speciality_name',
            ]);
            // ->add('Save',SubmitType::class);
            // ->add('role', ChoiceType::class, [
            //     'label' => 'Choose your role:',
            //     'choices' => [
            //         'Client' => 'ROLE_CLIENT',
            //         'Pro' => 'ROLE_PRO',
            //     ],
            // ]);
    

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
