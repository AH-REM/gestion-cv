<?php

namespace App\Form;

use App\Entity\IntervenantSearch;
use App\Entity\TypeEmploi;
use App\Entity\Diplome;
use App\Entity\Niveau;
use App\Entity\Domaine;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class IntervenantSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                    'autocomplete' => 'off'
                 ],
                'required' => false,
            ])
            ->add('prenom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom',
                    'autocomplete' => 'off'
                ],
                'required' => false,
            ])
            ->add('emploi', EntityType::class, [
                'class' => TypeEmploi::class,
                'label' => false,
                'placeholder' => 'Choisissez un emploi',
                'attr' => [ 'class' => 'select2-control' ],
                'required' => false,
            ])
            ->add('niveau', EntityType::class, [
                'class' => Niveau::class,
                'label' => false,
                'placeholder' => 'Choisissez un niveau',
                'choice_attr' => function(Niveau $niveau) {
                    return $niveau ? [ 'niveau' => $niveau->getNum() ] : [];
                },
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('diplome', EntityType::class, [
                'class' => Diplome::class,
                'label' => false,
                'placeholder' => 'Choisissez un diplome',
                'choice_attr' => function(Diplome $diplome) {
                    return $diplome ? [ 'niveau' => $diplome->getNiveau()->getNum() ] : [];
                },
                'attr' => ['class' => 'select2-control select2-control-diplome'],
                'required' => false,
            ])
            ->add('domaines', EntityType::class, [
                'class' => Domaine::class,
                'label' => false,
                'placeholder' => 'Choisissez un ou plusieurs domaines',
                'attr' => ['class' => 'select2-control-domaines'],
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IntervenantSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix() {
        return '';
    }
}
