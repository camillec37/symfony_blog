<?php


namespace App\Controller\Admin;


use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\SearchArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index(ArticleRepository $repository, Request $request)
    {

        //formulaire non relié à une table
        $searchForm = $this->createForm(SearchArticleType::class);

        $searchForm->handleRequest($request);

        dump($searchForm->getData());

        //tous les articles triés par date de publication décroissante
        //$articles = $repository->findBy([], ['publicationDate'=> 'DESC']);

        //méthode search que l'on a créée dans le repository et qui attend
        //un tableau comme paramètre => on force le typage pour passe un
        //tableau vide s'il n'y a pas de résultat
        $articles = $repository->search((array)$searchForm->getData());

        return $this->render('admin/article/index.html.twig',
        [
            'articles' => $articles,
            'search_form' => $searchForm->createView()
        ]
        );
    }


    /**
     * @Route("/edition/{id}", defaults={"id":null}, requirements={"id": "\d+"})
     */
    public function edit(Request $request, EntityManagerInterface $manager, ArticleRepository $repository, $id)
    {

        $originalImage = null;
        $user = $this->getUser();

        if(is_null($id)){
            $article = new Article();

            $article
                //on peut faire ca - si on ne met rien 'now' par defaut
                //ou dans le constructeur de l'entité article
                //->setPublicationDate(new \DateTime())

                //methode fourni par le framework pour récupérer le user
                ->setAuthor($user);


        } else {
            //ou $article = $manager->find(Article::class, $id)
            $article = $repository->find($id);

            if(!is_null($article->getImage())){

                //nom du fichier venant de la bdd
                $originalImage = $article->getImage();

                //le champ de formulaire attend un objet File
                //utilisant le chemin vers le fichier
                $article->setImage(
                    new File($this->getParameter('upload_dir').$article->getImage())
                );
            }
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted()){

            if($form->isValid()){

                /** @var UploadedFile|null $image */
                $image= $article->getImage();

                //si une image a été uploadée
                if(!is_null($image)){
                    //nom sous lequel on va enregistrer l'image
                    $filename = uniqid().'.'.$image->guessExtension();

                    //déplacement de l'image
                    $image->move(
                        //vers -> le parametre définit dans le fichier de config/services.yaml
                        $this->getParameter('upload_dir'),
                        //avec ce nom
                        $filename
                    );

                    //pour enregistrer le nom du fichier en bdd
                    $article->setImage($filename);

                    //s'il existe deja une image pour cet article
                    //on la supprime
                    if(!is_null($originalImage)){
                        unlink(($this->getParameter('upload_dir').$originalImage));
                    }

                } else {
                    //en modification, sans upload, on remets le nom
                    //de l'image venant de la bdd
                    $article->setImage($originalImage);
                }

                $manager->persist($article);
                $manager->flush();

                $this->addFlash('success', "L'article a bien été enregistré");

                return $this->redirectToroute('app_admin_article_index');

            } else {
                $this->addFlash('error', 'le formulaire contient des erreurs');
            }
        }
        return $this->render('admin/article/edit.html.twig',
         [
            'form' => $form->createView(),
             'original_image' => $originalImage
        ]);
    }

    /**
     * @Route("/suppression/{id}", requirements={"id": "\d+"})
     */
    public function delete(EntityManagerInterface $manager, Article $article)
    {

        //effacer l'image de l'article s'il en a une
        if(!is_null($article)){
            $image = $this->getParameter('upload_dir').$article->getImage();
            unlink($image);
        }

        //suppression en bdd
        $manager->remove($article);
        $manager->flush();

        $this->addFlash('success', "L'article est supprimé");

        return $this->redirectToRoute('app_admin_article_index');

    }

    /**
     * @Route("/comments/{id}")
     */
    public function Comments(Article $article)
    {

        return $this->render('admin/article/comments.html.twig',
        [
            'article' =>$article
        ]);
    }

    /**
     * @Route("/comments/delete/{id}")
     */
    public function deleteComments(Comment $comment, EntityManagerInterface $manager, $id)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash('success', "Le commentaire est bien supprimé");

        return $this->redirectToRoute('app_admin_article_comments',
        [
            'id' => $comment->getArticle()->getId()
        ]);

    }

    /**
     * @Route("/ajax-content/{id}")
     */
    public function ajaxContent(Article $article)
    {
        return new Response(nl2br($article->getContent()));
    }

}