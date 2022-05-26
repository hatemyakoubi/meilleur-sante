<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ResetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('plainPassword',PasswordType::class,[
                'attr'=>[
                    'placeholder'=>'Ancien mot de passe',
                ],
                'label'=>false,
                "required" => true,
                "mapped" => false,
                "constraints" => [
                    new NotBlank(["message" => "cette champs ne peut pas étre vide"]),
                    new UserPassword(["message" => "mot incorrecte"])
                ]
            ])
            ->add("password", RepeatedType::class, [
                'type'=>PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identiques',
                //'options' => ['attr' => ['placeholder' => 'Saisir un nouveau mot de passe ']],
                    'first_options'  => ['label' => False, 'attr'=>['placeholder'=>'Mot de passe']],
                    'second_options' => ['label' => False,'attr'=>['placeholder'=>'Répeter mot de passe']],
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'cette champs ne peut etre vide'])
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label'=>'Réinitialiser le mot de passe' 

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    
    }
}
