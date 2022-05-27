<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom',TextType::class,[
                'label'=>'Nom'
            ])
            ->add('prenom',TextType::class,[
                'label'=>'Prénom'
            ])
            ->add('email',EmailType::class)
            ->add("roles", ChoiceType::class, [
                'choices'=>[
                "Docteur"=>"ROLE_USER",
                "Administarteur"=>"ROLE_ADMIN",
                 ],
            ])
            ->add('password',PasswordType::class,[
                'label'=>'Mot de passe'
            ])
        ;
         // Data transformer
         $builder->get('roles')
         ->addModelTransformer(new CallbackTransformer(
             function ($rolesAsArray) {
                  return count($rolesAsArray) ? $rolesAsArray[0]: null;
             },
             function ($rolesAsString) {
                  return [$rolesAsString];
             }
         ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
