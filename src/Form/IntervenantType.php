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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Validator\Constraints\File;

use Doctrine\ORM\EntityManagerInterface;

class IntervenantType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('nom', TextType::class, [
                'attr' => [ 'autocomplete' => 'off' ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [ 'autocomplete' => 'off' ]
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email',
                'attr' => [ 'autocomplete' => 'off' ],
                'required' => true
            ])
            ->add('adresse', TextType::class, [
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
            ->add('emploi', EntityType::class, [
                'class' => TypeEmploi::class,
                'label' => 'Emploi',
                'placeholder' => 'Choisissez un emploi',
                'attr' => ['class' => 'select2-control-emploi'],
                'required' => true
            ])
            ->add('niveau', EntityType::class, [
                'class' => Niveau::class,
                'label' => 'Niveau du diplome',
                'placeholder' => 'Choisissez un niveau',
                'choice_attr' => function(Niveau $niveau) {
                    return $niveau ? [ 'niveau' => $niveau->getNum() ] : [];
                },
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('diplome', EntityType::class, [
                'class' => Diplome::class,
                'label' => 'Diplome',
                'placeholder' => 'Choisissez un diplome',
                'choice_attr' => function(Diplome $diplome) {
                    return $diplome ? [ 'niveau' => $diplome->getNiveau()->getNum() ] : [];
                },
                'attr' => [ 'class' => 'select2-control-diplome' ],
                'required' => true
            ])
        ;

        $modifierEmploi = function (FormInterface $form, ?TypeEmploi $emploi = null) {

            $form->add('emploi', EntityType::class, [
                'class' => TypeEmploi::class,
                'label' => 'Emploi',
                'placeholder' => 'Choisissez un emploi',
                'data' => $emploi,
                'attr' => ['class' => 'select2-control-emploi'],
                'required' => true
            ]);

        };

        $modifierDiplome = function (FormInterface $form, ?Diplome $diplome = null) {

            $form->add('diplome', EntityType::class, [
                'class' => Diplome::class,
                'label' => 'Diplome',
                'placeholder' => 'Choisissez un diplome',
                'choice_attr' => function(Diplome $diplome) {
                    return $diplome ? [ 'niveau' => $diplome->getNiveau()->getNum() ] : [];
                },
                'data' => $diplome,
                'attr' => ['class' => 'select2-control-diplome'],
                'required' => true
            ]);

        };

        $builder->get('emploi')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($modifierEmploi) {

            $emploi = $event->getForm()->getData();

            if (!$emploi) {
                $emploi = new TypeEmploi();
                $emploi->setLibelle($event->getData());

                $this->entityManager->persist($emploi);
                $this->entityManager->flush();
            }

            $modifierEmploi($event->getForm()->getParent(), $emploi);
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

            if ($event->getData() != $diplome->getNiveau()->getId()) {
                $this->entityManager->persist($diplome);
                $this->entityManager->flush();
            }

            $modifierDiplome($event->getForm()->getParent(), $diplome);
            $event->getForm()->getParent()->getData()->setDiplome($diplome);

        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intervenant::class,
        ]);
    }
}
