<?php
/**
 * Created by PhpStorm.
 * User: actar
 * Date: 20/02/2019
 * Time: 21:27
 */

namespace EcoBundle\Controller\DashboardAdmin;

use EcoBundle\Entity\AnnonceRep;
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
 * @Route("da")
 */
class DARepController extends Controller
{


    /**
     *
     * @Route("/rep", name="da_rep_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }


        $annoncerep = $this->getDoctrine()->getManager()->getRepository('EcoBundle:AnnonceRep')->findAll();
        $reparation = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Reparation')->findAll();
        $demande = $this->getDoctrine()->getManager()->getRepository('EcoBundle:DemeandeC')->findAll();


        return $this->render('@Eco/DashboardAdmin/Reparateur/indexrep.html.twig', array(
            'annoncerep' => $annoncerep,'reparation'=> $reparation,'demande'=>$demande

        ));
    }

    /**
     *
     * @Route("/grant{id}", name="da_rep_grant")
     * @Method("GET")
     */
    public function grantAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EcoBundle:Reparateur')->find($id);
        $rep->setType('Professionel');
        $em->flush();
//Redirect to the read
            return $this->redirectToRoute('da_rep_index');



    }
    /**
     * Finds and displays a annonce entity.
     *
     * @Route("/rep/{id}", name="da_rep_show")
     * @Method("GET")
     */
    public function showAction(AnnonceRep $annonceRep)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $deleteForm = $this->createDeleteForm($annonceRep);

        return $this->render('@Eco/DashboardAdmin/Reparateur/showrep.html.twig', array(
            'annoncerep' => $annonceRep,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing rep entity.
     *
     * @Route("/rep/{id}/edit", name="da_rep_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AnnonceRep $annonceRep)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $deleteForm = $this->createDeleteForm($annonceRep);
        $editForm = $this->createForm('EcoBundle\Form\AnnonceRepType', $annonceRep);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('da_rep_edit', array('id' => $annonceRep->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Reparateur/editrep.html.twig', array(
            'user' => $annonceRep,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AnnonceRep entity.
     *
     * @Route("/rep/{id}", name="da_rep_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AnnonceRep $annonceRep)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $form = $this->createDeleteForm($annonceRep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($annonceRep);
            $em->flush();
        }

        return $this->redirectToRoute('da_rep_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param AnnonceRep $annonceRep The AnnounceReparation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AnnonceRep $annonceRep)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('da_rep_delete', array('id' => $annonceRep->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @Route("/rep/compte/delete/{id}", name="da_compteprof_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deletedemandeComptefAction(Request $request, $id)
    {
        $demande = $this->getDoctrine()->getRepository('EcoBundle:DemeandeC')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($demande);
        $em->flush();
        return $this->redirectToRoute('da_rep_index');
    }

    /**
     * @Route("/rep/reparation/delete/{id}", name="da_reparation_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deletereparationdemandeComptefAction(Request $request, $id)
    {
        $demande = $this->getDoctrine()->getRepository('EcoBundle:Reparation')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($demande);
        $em->flush();
        return $this->redirectToRoute('da_rep_index');
    }

    /**
     *Annuler  a Rep entity.
     *
     * @Route("/rep/annuler/{id}", name="da_rep_annuler")
     * @Method("GET")
     */
    public function annulerAction(Request $request)
    {

        $reparation = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Reparation')
            ->find($request->get('id'));
        $reparation->setStatut("Annuler");

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('da_rep_index');


    }

    /**
     *Annuler  a Rep entity.
     *
     * @Route("/rep/valider/{id}", name="da_rep_valider")
     * @Method("GET")
     */
    public function validerAction(Request $request)
    {

        $demande = $this->getDoctrine()->getManager()->getRepository('EcoBundle:DemeandeC')
            ->find($request->get('id'));
        $user=$demande->getReparateur();
        $user->setType("Professionel");
        $this->getDoctrine()->getManager()->remove($demande);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('da_rep_index');


    }




}