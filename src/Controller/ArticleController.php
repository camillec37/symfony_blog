<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/{id}")
     */
    public function index(Article $article, CommentRepository $repository, EntityManagerInterface $manager, Request $request)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted()){

            if($form->isValid()){

                $comment
                    ->setUser($this->getUser())
                    //on a mis un constructeur dans la classe comment ou on peut faire:
                    //->setpublicationDate(new \DateTime())
                    ->setArticle($article)
                ;


             $manager->persist($comment);
             $manager->flush();

             $this->addFlash('success','Votre commentaire a bien été enregistré' );

             //on redirige vers la même page pour ne pas que le rafraichissement
             //fasse le commentaire enregistré une 2eme fois - en GET et non en POST
             //Et le textarea ne garde pas le text du commentaire
             return $this->redirectToRoute('app_article_index',
             [
                 'id' => $article->getId()
             ]
            );

            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }

        }

        //inutile car on utilise le lazy loading directement dans le html
        /*$comments = $repository->findBy(
            ['article'=> $article],
            ['publicationDate'=>'DESC']
        );*/





        return $this->render('article/index.html.twig',
            [
                'article' => $article,
                'form' => $form->createView(),
                //inutile car on utilise le lazy loading directement dans le html
                //'comments' => $comments
            ]
        );
    }

}
