<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('lieu', EntityType::class,[
                'class' => Lieu::class,
                'label' => 'Lieu:',
                'choice_label' => 'nom',
                'required' => true
            ])
           /* ->add('campus', EntityType::class, [
                'label' => 'Campus',
                'class' => Campus::class,
                'data' =>
            ])
           */

        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
