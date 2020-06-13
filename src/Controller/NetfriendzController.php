<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


// use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;




class NetfriendzController extends AbstractController
{
    /**
     * @Route("/netfriendz", name="netfriendz")
     */

     //les parametres ds l'index sont comme le $repo
    public function index(ArticleRepository $repo)
    {
        // $repo = $this->getDoctrine()->getRepository(article::class);

        $articles = $repo->findAll();

        return $this->render('netfriendz/index.html.twig', [
            'controller_name' => 'Netfriendz',
            'articles' => $articles 
        ]);
    }


    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('netfriendz/home.html.twig');
    }

    /**
     * @Route("/netfriendz/new", name="article_create")
     * @Route("/netfriendz/{id}/edit", name="blog_edit")
     */

    public function form(Article $article = null, Request $request, EntityManagerInterface $manager){
        //  dump($request);
        //creer un article vide qui contiendra les datas du formulaire
        if(!$article){
            $article = new Article();
        }
        
        
        //creer formulaire 
        // $form = $this->createFormBuilder($article)
        //                 ->add('title')

        //                 ->add('n')

        //                 ->add('image')
                        
        //                 ->getForm();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()){
                $article->setCreatedAt(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show',  [
            //afficher le form avec twig 
            'id' => $article->getId()
        ]);
        }
        
        return $this->render('netfriendz/create.html.twig', [
            'formArticle' =>$form->createView(),
            'editMode' => $article->getId() !== null
        ]);
        
    }

    /**
     * @Route("/netfriendz/{id}", name="blog_show")
     */

    
    public function show(Article $article){
        // $repo = $this->getDoctrine()->getRepository(Article::class);

        // $article = $repo->find($id);

        return $this->render('netfriendz/show.html.twig', [
            'article' => $article
        ]);
    }
}

 
