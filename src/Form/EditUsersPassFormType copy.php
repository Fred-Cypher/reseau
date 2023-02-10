<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditUsersPassFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'auto-complete' => 'new-password',
                    'class' => 'form-control'
                ],
                'label' => 'Mot de passe :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefaults([
            'data-class' => Users::class,
        ]);
    }
}

/*

->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Entrez votre nouveau mot de passe : ',
            ]);
->add('password', PasswordType::class, [
                'label' => 'Entrez votre nouveau mot de passe',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'auto-complete' => 'new-password',
                    'class' => 'form-control'
                ],
                'label' => 'Mot de passe :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ]
                ]);

            */