<?php

namespace App\Form\Admin;

use App\Entity\Users;
use PHPUnit\Framework\Constraint\IsEmpty;
use Symfony\Component\CssSelector\Parser\Shortcut\EmptyStringParser;
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
        $roles = $this->getParent('security.roel_hierarchy.roles');

        $builder
            ->add('email', EmailType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Adresse email : '
            ])
            ->add('nickname', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Pseudo : '
            ])
            ->add('roles', ChoiceType::class,
                array(
                    'attr' => array('class' => 'form-control') ,
                    'label' => 'Ajouter un rôle : 
                    Pour ajouter plusieurs rôles, ajouter seulement le plus élevé',
                    'choices' => 
                    array
                    (
                        'ROLE_ADMIN' => array
                        (
                            'Ajouter le rôle Administrateur' => 'ROLE_ADMIN'
                        ),
                        'ROLE_MODO' => array
                        (
                            'Ajouter le rôle Modérateur' => 'ROLE_MODO'
                        ),
                        'ROLE_USER' => array
                        (
                            'Ajouter le rôle Utilisateur' => ''
                        ),
                    ),
                    'multiple' => true,
                    'required' => true,
                )) 
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




*/





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