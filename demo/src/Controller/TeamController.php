<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Team;
use App\Form\TeamType;

use App\Repository\TeamRepository;

class TeamController extends AbstractController
{
     /**
     * @Route("/equipe", name="equipe")
     */
    public function team(TeamRepository $repo)
    {
        $team = $repo->findAll();
        return $this->render('team/team.html.twig', [
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

        return $this->render('team/gestTeam.html.twig',[
            "formTeam" => $form->createView(),
            'editMode' => $team->getId() !== null
        ]);
    }

    /**
     * @Route("/equipe/{id}", name="equipe_show")
     */
    public function team_show(Team $team)
    {
        return $this->render('team/teamShow.html.twig', [
            "team" => $team
        ]);
    }
}
