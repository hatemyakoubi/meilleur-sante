<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identificaion',NumberType::class,[
                'label'=>'Identification'
            ])
            ->add('nomComplet',TextType::class,[
                'label'=>'Nom Complet'
            ])
            ->add('sexe',ChoiceType::class,[
                'label'=>'Sexe',
                //'expanded'=>true,
                'choices'=>[
                    'Male'=>'Male',
                    'Female'=>'Female',
                ]
            ])
            ->add('age',NumberType::class,[
                'label'=>'Age'
            ])
            ->add('localisation',TextType::class,[
                'label'=>'Adresse'
            ])
            ->add('temperature',NumberType::class,[
                'label'=>'Température'
            ])
            ->add('pouls',NumberType::class,[
                'label'=>'Pouls'
            ])
            ->add('oxygene',NumberType::class,[
                'label'=>'Oxygène'
            ])
            ->add('target')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
