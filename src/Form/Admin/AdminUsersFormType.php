<?php

namespace App\Form\Admin;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUsersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = $this->getParent('security.role_hierarchy.roles');

        $builder
            ->add('email', EmailType::class,[
                'attr' => [
                    'class' => 'form-control my-2'
                ],
                'label' => 'Adresse email : '
            ])
            ->add('nickname', TextType::class, [
                'attr' => [
                    'class' => 'form-control my-2'
                ],
                'label' => 'Pseudo : '
            ])
            ->add('roles', ChoiceType::class,[
                'attr' => [
                    'class' => 'form-check d-flex justify-content-evenly align-items-center col-sm-12 col-md-8 col-lg-7 mt-2',
                ],
                'label' => 'Sélectionner les rôles à assigner l\'utilisateur, plusieurs choix possibles : ',
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Modérateur' => 'ROLE_MODO',    
                    'Utilisateur' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('isEnabled', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check form-check-inline d-flex justify-content-evenly align-items-center col-sm-12 col-md-5 col-lg-4 mt-2'
                ],
                'label' => 'Utilisateur autorisé / bloqué',
                'choices' => [
                    'Bloqué' => 0,
                    'Autorisé' => 1
                ],
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}


/*
->add('roles', EntityType::class, [
    'class' => Users::class,
    'choice_label' => 'roles',
    'multiple' => true
])
->add('roles', CollectionType::class, [
    'entry_type' => ChoiceType::class,
    'entry_options' => [
        'choices' => [
            'Administrateur' => 'ROLE_ADMIN',
            'Modérateur' => 'ROLE_MODO',
            'Utilisateur' => 'ROLE_USER',
            'Pas de rôle spécifique' => '',
        ],
    ],
    'label' => 'Roles : '
])
->add('roles', Users::class,
    array('label' => 'Rôles : ',
            'class' => Users::class,
            'property' => 'roles',
            'required' => true))
*/