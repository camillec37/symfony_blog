<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //formulaire en GET (POST par défaut)
            ->setMethod('GET')
            ->add(
                'title',
                TextareaType::class,
                [
                    'label' => 'Titre',
                    'required' => false
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'label' => 'Categorie',
                    'required' => false,
                    'class' => Category::class,
                    //comme on a relié a une classe, on doit dire ce que l'on veut
                    //afficher dans le menu deroulant depuis la classe
                    'choice_label' => 'name',
                    'placeholder' => 'Choisissez une catégorie'

                ]
            )
            ->add(
                'start_date',
                DateType::class,
                [
                    'label' => 'Date de début',
                    'required' => false,
                    //un seul input, sinon J/M/Y séparés par défaut
                    'widget' => 'single_text'

                ]
            )
            ->add(
                'end_date',
                DateType::class,
                [
                    'label' => 'Date de fin',
                    'required' => false,
                    'widget' => 'single_text'

                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
