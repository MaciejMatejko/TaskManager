<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
                ->add("name")
                ->add("description")
                ->add('users', 'entity', array('class' => 'AppBundle:User', 'choice_label' => 'username', 'multiple' => true))
                ->add("add", "submit")
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
        
        return $this->render("AppBundle:Team:new.html.twig", ["form" => $form->createView()]);
    }
    
    /**
    * @Route("/{id}/edit")
    * @Template()
    * @Security("has_role('ROLE_MANAGER')")
    */
    public function editAction(Request $request, $id)
    {
        $team = $this->getDoctrine()->getRepository("AppBundle:Team")->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$team){
            throw $this->createNotFoundException("Team not found");
        }
        if($user->getTeam() == null || $team->getId() !== $user->getTeam()->getId()){
            $this->redirectToRoute("app_user_error403");
        }
        
        $form = $this->createFormBuilder($team)
                ->add("name")
                ->add("description")
                ->add('users', 'entity', array('class' => 'AppBundle:User', 'choice_label' => 'username', 'multiple' => true))
                ->add("edit", "submit")
                ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isValid()){
            $users=$team->getUsers();
            foreach($users as $user){
                $user->setTeam($team);
            }
            $em = $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute("app_user_main");
        }
        
        return $this->render("AppBundle:Team:edit.html.twig", ["team" => $team, "form" => $form->createView()]);
    }
    
    /**
    * @Route("/{id}/delete")
    * @Template()
    * @Security("has_role('ROLE_MANAGER')")
     */
    public function deleteAction(Request $request, $id)
    {
        $team = $this->getDoctrine()->getRepository("AppBundle:Team")->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$team){
            throw $this->createNotFoundException("Team not found");
        }
        if($user->getTeam() == null || $team->getId() !== $user->getTeam()->getId()){
            $this->redirectToRoute("app_user_error403");
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();
        
        return $this->redirectToRoute("app_user_main");
    }
    
    /**
    * @Route("/{id}/show")
    * @Template()
    */
    public function showAction($id)
    {
        $team = $this->getDoctrine()->getRepository("AppBundle:Team")->find($id);
        
        if(!$team){
            throw $this->createNotFoundException("Team not found");
        }
        
        return ["team" => $team];
    }
    
    /**
     * @Route("/{id}/{userId}/remove")
     */
    public function removeAction($id, $userId)
    {
        $team = $this->getDoctrine()->getRepository("AppBundle:Team")->find($id);
        
        if(!$team){
            throw $this->createNotFoundException("Team not found");
        }
        
        $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($userId);
        
        if(!$user){
            throw $this->createNotFoundException("User not found");
        }
        
        $em = $this->getDoctrine()->getManager();
        $team->removeUser($user);
        $user->setTeam(null);
        $em->flush();
        
        return $this->redirectToRoute("app_team_edit", ["id" => $id]);
    }
}
