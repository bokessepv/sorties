<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltresSortiesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Campus', EntityType::class, [
                'class'=>Campus::class,
                'choice_label'=>'nom'
            ])
            ->add('Le nom de la sortie contient')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionMax')
            ->add('infosSortie')
            ->add('etat')
            ->add('lieu')
            ->add('participants')
            ->add('organisateur')
            ->add('campusOrganisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
