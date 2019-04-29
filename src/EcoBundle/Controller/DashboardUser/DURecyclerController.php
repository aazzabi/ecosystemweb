<?php
/**
 * Created by PhpStorm.
 *
 * Date: 14/02/2019
 * Time: 22:04
 */

namespace EcoBundle\Controller\DashboardUser;

use EcoBundle\Entity\CategorieMission;
use EcoBundle\Entity\Missions;

use EcoBundle\Entity\Group;
use EcoBundle\Entity\Livreur;
use EcoBundle\Entity\Reparateur;
use EcoBundle\Entity\RespAsso;
use EcoBundle\Entity\RespSoc;
use EcoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @Route("du")
 */

class DURecyclerController extends Controller
{
    /**
     * @Route("/missions", name="du_missions_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        $events = array();

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $evenements = $em->getRepository('EcoBundle:Missions')->findBy(array('createdBy'=>$user));

        foreach ($evenements as $e) {
            if ($e->getCreatedBy()== $user) {
                $events[] = $e;
            }

        }

        return $this->render('@Eco/DashboardUser/Missions/index.html.twig', array(
            'evenements' => $evenements,
        ));
    }

    /**
     *
     * @Route("/missions/new", name="du_missions_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $missions = new Missions();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm('EcoBundle\Form\MissionsType', $missions);
        $form->handleRequest($request);
        $missions->setCreatedBy($user);
        $missions->setNbVues(0);
        $missions->setAtteint(0);


//        $user->addMissionsCrees($missions);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($missions);
            $em->flush();
            return $this->redirectToRoute('du_missions_index');
            $this->addFlash("success", "Votre mission a été ajoutée avec succés ! ");

        }
        return $this->render('@Eco/DashboardUser/Missions/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/missions/{id}/edit", name="du_missions_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Missions $evenement)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($evenement->getCreatedBy()!= $user)
        {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        $editForm = $this->createForm('EcoBundle\Form\MissionsType', $evenement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('du_missions_index', array('id' => $evenement->getId()));
        }

        return $this->render('@Eco/DashboardUser/Missions/edit.html.twig', array(
            'evenement' => $evenement,
            'form' => $editForm->createView(),
        ));
    }


    /**
     * @Route("/missions/{id}", name="du_missions_show")
     * @Method("GET")
     */
    public function showAction(Missions $evenement)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($evenement->getCreatedBy()!= $user)
        {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        return $this->render('@Eco/DashboardUser/Missions/show.html.twig', array(
            'evenement' => $evenement,
        ));
    }

    /**
     * Deletes a Reparateur entity.
     *
     * @Route("/missions/delete/{id}", name="du_missions_delete")
     */
    public function deleteEventAction($id)
    {
        $m=$this->getDoctrine()->getManager();
        $evenement = $m->getRepository(Missions::class)->find($id);
        $m->remove($evenement);
        $m->flush();

        return$this->redirectToRoute('du_missions_index');
    }


}