<?php

namespace EcoBundle\Controller;

use EcoBundle\Entity\Reparateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Reparateur controller.
 *
 * @Route("reparateur")
 */
class ReparateurController extends Controller
{
    /**
     * Lists all Reparateur entities.
     *
     * @Route("/", name="reparateur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reparateurs = $em->getRepository('EcoBundle:Reparateur')->findAll();

        return $this->render('@Eco/Reparateur/index.html.twig', array(
            'reparateurs' => $reparateurs,
        ));
    }

    /**
     * Creates a new Reparateur entity.
     *
     * @Route("/new", name="reparateur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $reparateur = new Reparateur();
        $form = $this->createForm(  'EcoBundle\Form\ReparateurType', $reparateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reparateur);
            $em->flush();

            return $this->redirectToRoute('reparateur_show', array('id' => $reparateur->getId()));
        }

        return $this->render('@Eco/Reparateur/new.html.twig', array(
            'Reparateur' => $reparateur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Reparateur entity.
     *
     * @Route("/{id}", name="reparateur_show")
     * @Method("GET")
     */
    public function showAction(Reparateur $reparateur)
    {
        $deleteForm = $this->createDeleteForm($reparateur);

        return $this->render('@Eco/Reparateur/show.html.twig', array(
            'reparateur' => $reparateur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Reparateur entity.
     *
     * @Route("/{id}/edit", name="reparateur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Reparateur $reparateur)
    {
        $deleteForm = $this->createDeleteForm($reparateur);
        $editForm = $this->createForm('EcoBundle\Form\ReparateurType', $reparateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reparateur_edit', array('id' => $reparateur->getId()));
        }

        return $this->render('@Eco/Reparateur/edit.html.twig', array(
            'Reparateur' => $reparateur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Reparateur entity.
     *
     * @Route("/{id}", name="reparateur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Reparateur $reparateur)
    {
        $form = $this->createDeleteForm($reparateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reparateur);
            $em->flush();
        }

        return $this->redirectToRoute('reparateur_index');
    }

    /**
     * Creates a form to delete a Reparateur entity.
     *
     * @param Reparateur $reparateur The Reparateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Reparateur $reparateur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reparateur_delete', array('id' => $reparateur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
