<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
// use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;

use App\Repository\ArticleRepository;

use App\Form\ArticleType;
use App\Form\CommentType;
use App\Form\CategoryType;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     *  @Route("/", name="home")
     */
    public function home(){
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenue sur mon site !'
        ]);
    }
    
    /**
     * @Route("/blog/new", name="newArticle")
     * @Route("/blog/{id}/edit", name="editArticle")
     */
    public function gestionArticle(Article $article = null, Request $req, ManagerRegistry $mr){
        
        if(!$article)
            $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId())
                $article->setCreateAt(new \DateTime());

            $manager = $mr->getManager();
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }
        
        return $this->render('blog/createArticle.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getID() !== null //false si il est nul donc si on est pas en mode edit
        ]);
    }


     /**
     *  @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article, Request $req, ManagerRegistry $mr){
        // SI IL N'Y AVAIT QUE L'ID EN PARAMETRE ET QUE LE REPOSITORY ETAIT ABSENT DU FICHIER
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        // $article = $repo->find($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setArticle($article);
            $comment->setCreateAt(new \DateTime());
            
            $manager = $mr->getManager();
            $manager->persist($comment);
            $manager->flush();
        }

        return $this->render('blog/show.html.twig', [
            "formComment" => $form->createView(),
            "article" => $article
        ]);
    }

    /**
     * @Route("/blog/cat/new", name="newCategory")
     */
    public function newCat(Request $req, ManagerRegistry $mr){
        
        $cat = new Category();

        $form = $this->createForm(CategoryType::class, $cat);
        
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){

            $manager = $mr->getManager();
            $manager->persist($cat);
            $manager->flush();

            return $this->redirectToRoute('home');
        }
        
        return $this->render('blog/createCategory.html.twig', [
            'formCategory' => $form->createView()
        ]);
    }
   

}
