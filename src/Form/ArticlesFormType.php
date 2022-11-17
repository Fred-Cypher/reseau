<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesFormType extends AbstractType
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
            ->add('summary', TextType::class, [
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Résumé de l\'article : ',
                'help' => 'Limité à 300 caractères.',
                'help_attr' => [
                    'class' => 'small text-secondary'
                ]
            ])
            ->add('content', TextareaType::class,[
                'attr' => [
                    'class' => 'form-control mt-2'
                ],
                'label' => 'Contenu de l\'article : '
            ])
            ->add('user', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'nickname',
                'attr' => [
                    'class' => 'd-none disabled',
                ],
                'label' => ' ',
            ])
            /*->add('user', HiddenType::class, [
                'required' => true,
                'disabled' => false,
                'mapped' => true,
                
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
