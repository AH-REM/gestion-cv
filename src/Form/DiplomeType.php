<?php

namespace App\Form;

use App\Entity\Diplome;
use App\Entity\Niveau;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DiplomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libelle',
                'attr' => [ 'autocomplete' => 'off' ],
                'required' => true,
            ])
            ->add('niveau', EntityType::class, [
                'class' => Niveau::class,
                'label' => 'Niveau',
                'placeholder' => 'Choisissez un niveau',
                'choice_attr' => function(Niveau $niveau) {
                    return $niveau ? [ 'niveau' => $niveau->getNum() ] : [];
                },
                'choice_label' => 'name',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Diplome::class,
        ]);
    }
}
