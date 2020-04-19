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
use App\Entity\Team;
use App\Repository\ArticleRepository;
use App\Repository\TeamRepository;

use App\Form\TeamType;

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

        $form = $this->createFormBuilder($article)
                     ->add('title')
                     ->add('content')
                     ->add('image')
                     ->getForm();
        
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
    public function show(Article $article){
        // SI IL N'Y AVAIT QUE L'ID EN PARAMETRE ET QUE LE REPOSITORY ETAIT ABSENT DU FICHIER
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        // $article = $repo->find($id);

        return $this->render('blog/show.html.twig', [
            "article" => $article
        ]);
    }


    /**
     * @Route("/equipe", name="equipe")
     */
    public function team(TeamRepository $repo)
    {
        $team = $repo->findAll();
        return $this->render('blog/team.html.twig', [
            "team" => $team
        ]);
    }

    /**
     * @Route("/equipe/new", name="equipe_new")
     * @Route("/equipe/{id}/edit", name="equipe_edit")
     */
    public function gestionTeam(Team $team = null, Request $req, ManagerRegistry $mr){
        if(!$team)
            $team = new Team();
        
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){

            $manager = $mr->getManager();
            $manager->persist($team);
            $manager->flush();

            return $this->redirectToRoute('equipe_show', ['id' => $team->getId()]);      
        }

        return $this->render('blog/gestTeam.html.twig',[
            "formTeam" => $form->createView(),
            'editMode' => $team->getId() !== null
        ]);
    }

    /**
     * @Route("/equipe/{id}", name="equipe_show")
     */
    public function team_show(Team $team)
    {
        return $this->render('blog/teamShow.html.twig', [
            "team" => $team
        ]);
    }

}
