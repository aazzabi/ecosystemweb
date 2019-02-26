<?php

namespace EcoBundle\Controller\DashboardUser;

use EcoBundle\Entity\AnnonceRep;
use EcoBundle\Entity\CategoriePub;
use EcoBundle\Entity\CommentairePublication;
use EcoBundle\Entity\Livreur;
use EcoBundle\Entity\PublicationForum;
use EcoBundle\Entity\Reparateur;
use EcoBundle\Entity\Reparation;
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
class DURepController extends Controller
{
    /**
     *
     * @Route("/rep", name="du_rep_index")
     * @Method("GET")
     */
    public function indexAction()
    {
//        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
//        }
        $user = $this->get('security.token_storage')->getToken()->getUser();


        $announcerep = $this->getDoctrine()->getManager()->getRepository('EcoBundle:AnnonceRep')
            ->findBy(array('utilisateur' => $user));
        if (in_array('ROLE_REPARATEUR', $user->getRoles())) {
            $reparation = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Reparation')
                ->findBy(array('reparateur' => $user));
        } else {
            $reparation = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Reparation')
                ->findBy(array('utilisateur' => $user));
        }

        return $this->render('@Eco/DashboardUser/Reparateur/indexrep.html.twig', array(
            'annonce' => $announcerep, 'reparation' => $reparation
        ));
    }


    /**
     * Finds and displays a annonce entity.
     *
     * @Route("/rep/{id}", name="du_rep_show")
     * @Method("GET")
     */
    public function showAction(AnnonceRep $annonceRep)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($annonceRep->getUtilisateur() != $user) {
            throw new AccessDeniedException("Vous ne pouvez pas modifier cette publication !", Response::HTTP_FORBIDDEN);
        }
        $deleteForm = $this->createDeleteForm($annonceRep);

        return $this->render('@Eco/DashboardUser/Reparateur/showrep.html.twig', array(
            'annoncerep' => $annonceRep,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing rep entity.
     *
     * @Route("/rep/{id}/edit", name="du_rep_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AnnonceRep $annonceRep)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($annonceRep->getUtilisateur() != $user) {
            throw new AccessDeniedException("Vous ne pouvez pas modifier cette announce !", Response::HTTP_FORBIDDEN);
        }
        $deleteForm = $this->createDeleteForm($annonceRep);
        $editForm = $this->createForm('EcoBundle\Form\AnnonceRepType', $annonceRep);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('du_rep_edit', array('id' => $annonceRep->getId()));
        }

        return $this->render('@Eco/DashboardUser/Reparateur/editrep.html.twig', array(
            'user' => $annonceRep,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AnnonceRep entity.
     *
     * @Route("/rep/{id}", name="du_rep_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AnnonceRep $annonceRep)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($annonceRep->getUtilisateur() != $user) {
            throw new AccessDeniedException("Vous ne pouvez pas modifier cette announce !", Response::HTTP_FORBIDDEN);
        }
        $form = $this->createDeleteForm($annonceRep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($annonceRep);
            $em->flush();
        }

        return $this->redirectToRoute('du_rep_index');
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
            ->setAction($this->generateUrl('du_rep_delete', array('id' => $annonceRep->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     *Valider  a annonce entity.
     *
     * @Route("/rep/Valider/{id}", name="du_rep_valider")
     * @Method("GET")
     */
    public function validerAction(Request $request)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $announcerep = $this->getDoctrine()->getManager()->getRepository('EcoBundle:AnnonceRep')
            ->find($request->get('id'));
        if ($announcerep->getUtilisateur() != $user) {
            throw new AccessDeniedException("Vous ne pouvez pas Valider cette publication !", Response::HTTP_FORBIDDEN);
        }

        $reparation = new Reparation();
        $reparation->setUtilisateur($announcerep->getUtilisateur());
        $reparation->setReparateur($announcerep->getReparateur());
        $this->getDoctrine()->getManager()->remove($announcerep);

        $this->getDoctrine()->getManager()->persist($reparation);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('du_rep_index');


    }

    /**
     *Annuler  a Rep entity.
     *
     * @Route("/rep/annuler/{id}", name="du_rep_annuler")
     * @Method("GET")
     */
    public function annulerAction(Request $request)
    {

        $reparation = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Reparation')
            ->find($request->get('id'));
        $reparation->setStatut("Annuler");

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('du_rep_index');


    }

    /**
     *Valider  a Rep entity.
     *
     * @Route("/rep/Valider2/{id}", name="du_rep_valider2")
     * @Method("GET")
     */
    public function valider2Action(Request $request)
    {

        $reparation = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Reparation')
            ->find($request->get('id'));
        $reparation->setStatut("Terminer");
        $reparation->setCommentaire("Fini merci de venir récupérer votre objet ");

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('du_rep_index');


    }

    /**
     *Ajout commentaire in  a Rep entity.
     *
     * @Route("/rep/comment/{id}", name="du_rep_comment")
     * @Method("GET")
     */
    public function commentAction(Request $request)
    {

        $reparation = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Reparation')
            ->find($request->get('id'));

        $reparation->setCommentaire($request->get('comment'));

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('du_rep_index');


    }



}