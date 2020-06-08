<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'lastname',
                TextType::class,
                [
                    'label'=>'Nom'
                ]
            )
            ->add(
                'firstname',
                TextType::class,
                [
                    'label' =>'prénom'
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Email'
                ]
            )
            ->add(
                'plainPassword',
                //2 champs qui doivent avoir la même valeur
                RepeatedType::class,
                [
                    //input type password
                    'type' => PasswordType::class,
                    //options du premier des 2 champs
                    'first_options' => [
                        'label'=>'Mot de passe',
                        'help'=> 'Le mot de passe ne doit contenir que des lettres, des chiffres ou _ 
                        et faire entre 6 et 20 caractères'
                        ],
                    //options du 2eme champ
                    'second_options' => [
                        'label'=> 'Confirmation du mot de passe'
                    ],
                    // message d'erreur si les 2 champs n'ont pas la même valeur
                    'invalid_message' => 'La confirmation ne correspond pas au mot de passe'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
