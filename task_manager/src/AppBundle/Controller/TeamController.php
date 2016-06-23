<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/team")
 */
class TeamController extends Controller
{
    
    /**
     * @Route("/new")
     * @Template()
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function newAction(Request $request)
    {
        $team = new Team();
        $form = $this->createFormBuilder($team)
                ->add("name")->add("description")
                ->add('users', 'entity', array('class' => 'AppBundle:User', 'choice_label' => 'username', 'multiple' => true))
                ->add("submit", "submit")
                ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isValid()){
            $users=$team->getUsers();
            foreach($users as $user){
                $user->setTeam($team);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();
            
            return $this->redirectToRoute("app_user_main");
        }
        
        return ["form" => $form->createView()];
    }
}
