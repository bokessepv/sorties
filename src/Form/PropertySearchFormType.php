<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\PropertySearch;
use App\Entity\Sortie;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('searchCampus', StringType::class, [
                'class'=>Campus::class,
                'required'=>false,
                'label'=>false,
                'choice_label'=>'nom'
            ])
            ->add('searchName', StringType::class,[
                'class'=>Sortie::class,
                'required'=>false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'search'
                ]
            ])
            ->add('searchDate', StringType::class,[
                'class'=>Sortie::class,
                'required'=>false,
                'label'=>false
            ])
            ->add('searchOrganisateur', StringType::class,[
                'class'=>Sortie::class,
                'required'=>false,
                'label'=>false
            ])
            ->add('searchInscrit', StringType::class,[
                'class'=>Sortie::class,
                'required'=>false,
                'label'=>false
            ])
            ->add('searchNonInscrit', StringType::class,[
                'class'=>Sortie::class,
                'required'=>false,
                'label'=>false
            ])
            ->add('searchPassees', StringType::class,[
                'class'=>Sortie::class,
                'required'=>false,
                'label'=>false
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'Rechercher'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method'=>'get',
            'crsf_protection' =>false
        ]);
    }
}
