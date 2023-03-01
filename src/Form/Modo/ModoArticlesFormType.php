<?php

namespace App\Form\Modo;

use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModoArticlesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Titre de l\'article : '
            ])
            ->add('summary', TextType::class,[
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Résumé de l\'article : ',
                'help' => 'Limité à 300 caractères',
                'help_attr' => [
                    'class' => 'small text-secondary'
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Contenu de l\'article : '
            ])
            ->add('isVisible', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check form-check-inline d-flex justify-content-evenly align-items-center col-md-5 col-lg-4 mt-2'
                ],
                'label' => 'Bloquer / débloquer un article',
                'choices' => [
                    'Bloquer' => 0,
                    'Autoriser' => 1
                ],
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}