<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
            TextType::class,
                [
                    'label'=> 'Titre'
                ]
            )
            ->add('content',
            TextareaType::class,
                [
                    'label' => 'Contenu'
                ]
            )
            ->add('category',
            //ce type de champ créé un select sur les valeurs de la table
            EntityType::class,
            [
                'label' => 'Catégorie',
                //classe de l'entité
                'class' => Category::class,
                //attribut qui s'affiche dans le select
                'choice_label' => 'name',
                //pour avoir la première option vide et avec le texte que l'on veut
                //sinon ca sera le permier choix
                'placeholder' => 'Choisissez une catégorie'
            ]
            )
            ->add(
                'image',
                //input type file
                FileType::class,
                [
                    'label' => 'Illustration',
                    //champ optionnel
                    'required' => false
                ]
            )

            //ces 2 champs ne sont pas dans le formulaire
            //les valeurs seront définies dans le controleur
            //->add('publicationDate')
            //->add('author')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
