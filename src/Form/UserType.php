<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Role;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom de l\'utilisateur',
                'attr' => [ 'autocomplete' => 'off' ]
            ])
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'label' => 'Role',
                'placeholder' => 'Aucun role',
                'required' => false
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [ 'autocomplete' => 'off' ]
            ])
            ->add('confirm_password', PasswordType::class, [
                'label' => 'Confirmation du mot de passe',
                'attr' => [ 'autocomplete' => 'off' ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
