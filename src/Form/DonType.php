<?php

namespace App\Form;

use App\Entity\Don;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class DonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('quantitedon', null, [
            'attr' => [
                'class' => 'form-control mb-3',
                'placeholder' => 'Quantité de déchets données en kg'
            ],
            'label_attr' => [
                'class' => 'form-label text-uppercase fw-bold fs-5 mb-2',
            ],
            'label' => 'Quantité de déchets données'
        ])
        ->add('description', null, [
            'attr' => [
                'class' => 'form-control mb-3',
                'placeholder' => 'Description des déchets donnés'
            ],
            'label_attr' => [
                'class' => 'form-label text-uppercase fw-bold fs-5 mb-2'
            ]
        ])
        ->add('datedon', null, [
            'attr' => [
                'class' => 'form-control mb-3',
                'placeholder' => 'Date de don des déchets (jj/mm/aaaa)'
            ],
            'label_attr' => [
                'class' => 'form-label text-uppercase fw-bold fs-5 mb-2'
            ]
        ])
            ->add('TypeDechet')
            
            // ->add('recyclage')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Don::class,
        ]);
    }
}