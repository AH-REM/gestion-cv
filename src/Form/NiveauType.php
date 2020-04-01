<?php

namespace App\Form;

use App\Entity\Niveau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class NiveauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('num', NumberType::class, [
                'label' => 'Numéro',
                'required' => true,
            ])
            ->add('libelle', TextType::class, [
                'label' => 'Libelle',
                'attr' => [ 'autocomplete' => 'off' ],
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Niveau::class,
        ]);
    }
}
