<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom: ',
                'required' => true
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => "Date et heure de début: ",
                'html5' => true,
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => "Date limite d'inscription: ",
                'html5' => true,
                'widget' => 'single_text',
                'required' => true

            ])
            ->add('nbInscriptionMax', NumberType::class,[
                'label' => 'Nombre de places: ',
                'required' => true

            ])
            ->add('duree', NumberType::class, [
                'label' => 'Durée de la sortie: '
            ])
            ->add('infosSortie', TextType::class,[
                'label' => 'Description de la sortie: ',
                'required' => false
            ])

            ->add('photo', FileType::class,[
                'label'=> 'Photo (.jpg, .gif, .png, .svg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new  File([
                        'maxSize' => '500000k',
                        'maxSizeMessage' => 'Fichier trop lourd',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Format de l\'image non valide'
                    ])
                ]
            ])

        ;
        $builder
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'placeholder' => '',
                'choice_label' => 'nom',
                'mapped' => false
            ])
        ;

        $formModifier = function (FormInterface $form, Ville $ville = null) {
            $lieu = null === $ville ? [] : $ville->getLieu();


            $form->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'placeholder' => '',
                'choices' => $lieu,
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e.
                $data = $event->getData();

            }
        );

        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $ville = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $ville);
            }
        );
    }
//        $builder
//            ->add('ville', EntityType::class,[
//                'class' => Ville::class,
//                'placeholder' => 'Choissisez une ville',
//                'label' => 'Ville:',
//                'choice_label' => 'nom',
//                'mapped' => false
//            ])
//        ;
//
//        $formModifier = function(FormInterface $form, Ville $ville = null) {
//            $lieu = null === $ville ? [] : $ville->getLieu();
//
//            $form->add('lieu', EntityType::class, [
//                'class' => Lieu::class,
//                'placeholder' => '',
//                'choices' => $lieu
//            ]);
//    };
//
//        $builder->addEventListener(
//        FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) use ($formModifier) {
//                        // this would be your entity, i.e. Ville
//                        $data = $event->getData();
//
//                        $formModifier($event->getForm(), $data->getVille());
//                    }
//        );
//
//
//        $builder->get('ville')->addEventListener(
//            FormEvents::POST_SUBMIT,
//            function(FormEvent  $event)
//            {
//                $ville = $event->getForm()->getData();
//                $formModifier($event->getForm()->getParent(), $sport);
//
//            }
//        )
//








//
//
//            ->add('lieu', EntityType::class,[
//                'class' => Lieu::class,
//                'label' => 'Lieu:',
//                'choice_label' => 'nom',
//                'required' => true
//            ])

//           ->add('campus', EntityType::class, [
//                'label' => 'Campus',
//                'class' => Campus::class,
//                'choice_label' => 'nom',
//                'mapped' => false
//            ])







    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
