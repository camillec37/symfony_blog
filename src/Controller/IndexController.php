<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(ArticleRepository $repository)
    {
        $articles = $repository->findBy([], ['publicationDate' => 'DESC'], 3);

        return $this->render('index/index.html.twig',
            [
                'articles' => $articles
            ]);
    }

    /**
     * @Route("/contact")
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);

        //pour pré remplir les champs si utilisateur connecté
        if(!is_null($this->getUser())){

            $form->get('name')->setData($this->getUser());
            $form->get('email')->setData($this->getUser()->getEmail());

        }

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){

                //récupère toutes les données du formulaire sous
                //forme d'un tableau associatif
                $data= $form->getData();

                $mail= new Email();

                //renderview retourne le rendu d'un template sous forme
                //d'une chaine de caractères (au lieu d'un objet Response pour render)
                $mailBody = $this->renderView(
                    'index/contact_body.html.twig',
                    [
                       'data' => $data
                    ]
                );

                //voir dans .env pour la config
                $mail
                    ->subject('Nouveau message sur votre blog')
                    ->html($mailBody)
                    ->from('janest.demo@gmail.com')
                    ->to('julien.anest@vogue.fr')
                    ->replyTo($data['email'])
                ;

                $mailer->send($mail);

                $this->addFlash('success','Votre message est envoyé' );

                return $this->redirectToRoute('app_index_index');



            } else {
                $this->addFlash('error', 'le formulaire contient des erreurs');
            }
        }


        return $this->render(
            'index/contact.html.twig',
            [
            'form' => $form->createView()
            ]
        );
    }
}
