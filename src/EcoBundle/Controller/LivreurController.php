<?php

namespace EcoBundle\Controller;

use EcoBundle\Entity\Livreur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Livreur controller.
 *
 * @Route("livreur")
 */
class LivreurController extends Controller
{
    /**
     * Lists all livreur entities.
     *
     * @Route("/", name="livreur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $livreurs = $em->getRepository('EcoBundle:Livreur')->findAll();

        return $this->render('@Eco/livreur/index.html.twig', array(
            'livreurs' => $livreurs,
        ));
    }

    /**
     * Creates a new livreur entity.
     *
     * @Route("/new", name="livreur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $livreur = new Livreur();
        $form = $this->createForm('EcoBundle\Form\LivreurType', $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($livreur);
            $em->flush();

            return $this->redirectToRoute('livreur_show', array('id' => $livreur->getId()));
        }

        return $this->render('@Eco/livreur/new.html.twig', array(
            'livreur' => $livreur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a livreur entity.
     *
     * @Route("/{id}", name="livreur_show")
     * @Method("GET")
     */
    public function showAction(Livreur $livreur)
    {
        $deleteForm = $this->createDeleteForm($livreur);

        return $this->render('@Eco/livreur/show.html.twig', array(
            'livreur' => $livreur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing livreur entity.
     *
     * @Route("/{id}/edit", name="livreur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Livreur $livreur)
    {
        $deleteForm = $this->createDeleteForm($livreur);
        $editForm = $this->createForm('EcoBundle\Form\LivreurType', $livreur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('livreur_edit', array('id' => $livreur->getId()));
        }

        return $this->render('@Eco/livreur/edit.html.twig', array(
            'livreur' => $livreur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a livreur entity.
     *
     * @Route("/{id}", name="livreur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Livreur $livreur)
    {
        $form = $this->createDeleteForm($livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($livreur);
            $em->flush();
        }

        return $this->redirectToRoute('livreur_index');
    }

    /**
     * Creates a form to delete a livreur entity.
     *
     * @param Livreur $livreur The livreur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Livreur $livreur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('livreur_delete', array('id' => $livreur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
