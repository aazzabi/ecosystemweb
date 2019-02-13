<?php

namespace EcoBundle\Controller;

use EcoBundle\Entity\RespAssociation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Respassociation controller.
 *
 * @Route("respassociation")
 */
class RespAssociationController extends Controller
{
    /**
     * Lists all respAssociation entities.
     *
     * @Route("/", name="respassociation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $respAssociations = $em->getRepository('EcoBundle:RespAssociation')->findAll();

        return $this->render('@Eco/respassociation/index.html.twig', array(
            'respAssociations' => $respAssociations,
        ));
    }

    /**
     * Creates a new respAssociation entity.
     *
     * @Route("/new", name="respassociation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $respAssociation = new Respassociation();
        $form = $this->createForm('EcoBundle\Form\RespAssociationType', $respAssociation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($respAssociation);
            $em->flush();

            return $this->redirectToRoute('respassociation_show', array('id' => $respAssociation->getId()));
        }

        return $this->render('@Eco/respassociation/new.html.twig', array(
            'respAssociation' => $respAssociation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a respAssociation entity.
     *
     * @Route("/{id}", name="respassociation_show")
     * @Method("GET")
     */
    public function showAction(RespAssociation $respAssociation)
    {
        $deleteForm = $this->createDeleteForm($respAssociation);

        return $this->render('@Eco/respassociation/show.html.twig', array(
            'respAssociation' => $respAssociation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing respAssociation entity.
     *
     * @Route("/{id}/edit", name="respassociation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, RespAssociation $respAssociation)
    {
        $deleteForm = $this->createDeleteForm($respAssociation);
        $editForm = $this->createForm('EcoBundle\Form\RespAssociationType', $respAssociation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('respassociation_edit', array('id' => $respAssociation->getId()));
        }

        return $this->render('@Eco/respassociation/edit.html.twig', array(
            'respAssociation' => $respAssociation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a respAssociation entity.
     *
     * @Route("/{id}", name="respassociation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, RespAssociation $respAssociation)
    {
        $form = $this->createDeleteForm($respAssociation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($respAssociation);
            $em->flush();
        }

        return $this->redirectToRoute('respassociation_index');
    }

    /**
     * Creates a form to delete a respAssociation entity.
     *
     * @param RespAssociation $respAssociation The respAssociation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(RespAssociation $respAssociation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('respassociation_delete', array('id' => $respAssociation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
