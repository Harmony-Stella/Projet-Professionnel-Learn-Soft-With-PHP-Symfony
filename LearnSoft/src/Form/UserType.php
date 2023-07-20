<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           /* ->add('username')
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('contact')
            ->add('password')
           /* ->add('roles',RoleType::class)*/
            //->add('classe',ClasseType::class)
            //->add('eleve',EleveType::class)
            //->add('parent',ParentType::class)
            //->add('role')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
