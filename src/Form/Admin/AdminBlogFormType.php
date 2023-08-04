<?php

namespace App\Form\Admin;

use App\Entity\Images;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminBlogFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('title', TextType::class, [
                'attr'=> [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Titre de l\'image : '
            ])
            ->add('image_url', TextType::class, [
                'attr' => [
                    'class' => 'visually-hidden'
                ],
                'label' => ' '
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Description de l\'image (limité à 300 caractères) :'
            ])
            ->add('isVisible', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check form-check-inline d-flex justify-content-evenly align-items-center col-md-5 col-lg-4 mt-2'
                ],
                'label' => 'Bloquer / débloquer une image',
                'choices' => [
                    'Bloquer' => 0,
                    'Autoriser' => 1,
                ],
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}