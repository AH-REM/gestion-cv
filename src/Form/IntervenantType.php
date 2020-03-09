<?php

namespace App\Form;

use App\Entity\Intervenant;
use App\Entity\TypeEmploi;
use App\Entity\Diplome;
use App\Entity\Niveau;
use App\Entity\Domaine;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints\File;

class IntervenantType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        function emploiOptions($type = null) {
            $array = [
                'label' => 'Type Emploi',
                'placeholder' => 'Choisissez un emploi',
                'attr' => ['class' => 'select2-control-emploi'],
                //'multiple' => true,
                'required' => true
            ];
            if ($type) $array['data'] = $type;
            return $array;
        }

        function diplomeOptions($libelle = null) {
            $array = [
                'label' => 'Diplome',
                'placeholder' => 'Choisissez un diplome',
                'choice_attr' => function(Diplome $diplome) {
                    return $diplome ? [ 'niveau' => $diplome->getNiveau()->getNum() ] : [ 'niveau' => '' ];
                },
                'attr' => [ 'class' => 'select2-control-diplome' ],
                'required' => true
            ];
            if ($libelle) $array['data'] = $libelle;
            return $array;
        }

        function domainesOptions(Domaine $domaine = null) {
            $array = [
                'label' => 'Domaines',
                'placeholder' => 'Choisissez un domaine',
                'class' => Domaine::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'attr' => [ 'class' => 'select2-control-domaines' ],
                'required' => true
            ];
            if ($domaine) $array['data'] = $domaine;
            return $array;
        }

        $builder
            ->add('nom', null, [
                'attr' => [ 'autocomplete' => 'off' ]
            ])
            ->add('prenom', null, [
                'label' => 'Prénom',
                'attr' => [ 'autocomplete' => 'off' ]
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email',
                'attr' => [ 'autocomplete' => 'off' ],
                'required' => true
            ])
            ->add('adresse', null, [
                'attr' => [ 'autocomplete' => 'off' ],
                'required' => false
            ])
            ->add('cp', NumberType::class, [
                'label' => 'Code Postal',
                'attr' => [ 'autocomplete' => 'off' ],
                'required' => false,
            ])
            ->add('telFixe', TelType::class, [
                'label' => 'Téléphone Fixe',
                'attr' => [ 'autocomplete' => 'off' ],
                'required' => false
            ])
            ->add('telPortable', TelType::class, [
                'label' => 'Téléphone Portable',
                'attr' => [ 'autocomplete' => 'off' ],
                'required' => false
            ])
            ->add('emploi', null, emploiOptions())
            ->add('niveau', EntityType::class, [
                'label' => 'Niveau du diplome',
                'placeholder' => 'Choisissez un niveau',
                'class' => Niveau::class,
                'choice_label' => function ($niveau) {
                    return $niveau->getDisplayName();
                },
                'required' => true
            ])
            ->add('diplome', null, diplomeOptions())
            //->add('domaines', EntityType::class, domainesOptions())
            ->add('file', FileType::class, [
                'label' => 'CV',
                'attr' => [
                    'class' => 'intervenant_file_input',
                    'placeholder' => 'Choisissez un fichier PDF'
                ],
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
                'required' => true
            ])
            /*->add('divers', null, [
                'required' => false
            ])*/
        ;

        $modifierEmploi = function (FormInterface $form, String $type = null) {
            $form->add('emploi', null, emploiOptions($type));
        };

        $modifierDiplome = function (FormInterface $form, String $libelle = null) {
            $form->add('diplome', null, diplomeOptions($libelle));
        };

        /*$modifierDomaines= function (FormInterface $form, Domaine $domaine = null) {
            $form->add('domaines', EntityType::class, diplomeOptions($domaine));
        };*/

        $builder->get('emploi')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($modifierEmploi) {

            $emploi = $event->getForm()->getData();

            if (!$emploi) {
                $emploi = new TypeEmploi();
                $emploi->setLibelle($event->getData());
            }

            $modifierEmploi($event->getForm()->getParent(), $emploi->getLibelle());
            $event->getForm()->getParent()->getData()->setEmploi($emploi);

        });

        // Récupère le niveau
        $builder->get('niveau')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $event->getForm()->getParent()->getData()->setNiveau($event->getForm()->getData());
        });

        $builder->get('diplome')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($modifierDiplome) {

            $diplome = $event->getForm()->getData();

            if (!$diplome) {
                $diplome = new Diplome();
                $diplome->setLibelle($event->getData());
            }

            $niveau = $event->getForm()->getParent()->getData()->getNiveau();
            $diplome->setNiveau($niveau);

            $modifierDiplome($event->getForm()->getParent(), $diplome->getLibelle());
            $event->getForm()->getParent()->getData()->setDiplome($diplome);

        });

        /*$builder->get('domaines')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($modifierDomaines) {

            $domaines = $event->getForm()->getData();
            dump($domaines); die();

        });*/

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intervenant::class,
        ]);
    }
}
