<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorie")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/{id}", defaults={"id":null})
     */
    public function index(Category $category, ArticleRepository $repository, $id)
    {
        //pour avoir tous les articles, pas besoin du repository
        //$articles = $category->getArticles();

        $articles = $repository->findBy(
            [
                'category' => $category
            ],
            [
                'publicationDate' => 'DESC'
            ],
            3
        );



        return $this->render('category/index.html.twig',
            [
                'category' => $category,
                'articles' => $articles
            ]
        );
    }


    public function menu(CategoryRepository $repository)
    {
        //pour faire un findAll() avec la possibilité d'ajouter un ORDER BY
        $categories = $repository->findBy([],['id'=>'ASC']);

        return $this->render('category/menu.html.twig',
        [
            'categories' => $categories
        ]
        );

    }

}
