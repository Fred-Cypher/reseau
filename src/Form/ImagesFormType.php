<?php

namespace App\Form;

use App\Entity\Images;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImagesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Titre de l\'image : '
            ])
            ->add('image_url', TextType::class, [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Chemin de l\'image : '
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control mt-2'
                ], 
                'label' => 'Description de l\'image (limité 300 caractères) : '
            ]
            )
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}
