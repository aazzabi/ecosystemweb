<?php

namespace EcoBundle\Controller\DashboardAdmin;

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
class DAGroupController extends Controller
{
    /**
     * @Route("/group", name="da_groups_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();

        $association = $em->getRepository('EcoBundle:Group')->findAllAssociation();
//        var_dump($association[0]->getUsers());die;
        $societes = $em->getRepository('EcoBundle:Group')->findAllSociete();

        return $this->render('@Eco/DashboardAdmin/Group/index.html.twig', array(
            'associations' => $association,
            'societes' => $societes,
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/group/new", name="da_groups_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $group = new Group();
        $form = $this->createForm('EcoBundle\Form\GroupType', $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();

            return $this->redirectToRoute('da_groups_show', array('id' => $group->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Group/new.html.twig', array(
            'group' => $group,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a user entity.
     *
     * @Route("/group/{id}", name="da_groups_show")
     * @Method("GET")
     */
    public function showAction(Group $group)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $users= $group->getUsers();
        $deleteForm = $this->createDeleteForm($group);

        return $this->render('@Eco/DashboardAdmin/Group/show.html.twig', array(
            'users' => $users,
            'group' => $group,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/group/{id}/edit", name="da_groups_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Group $group)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $deleteForm = $this->createDeleteForm($group);
        $editForm = $this->createForm('EcoBundle\Form\UserType', $group);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('da_groups_edit', array('id' => $group->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Group/edit.html.twig', array(
            'user' => $group,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/user/{id}", name="da_groups_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Group $group)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $form = $this->createDeleteForm($group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($group);
            $em->flush();
        }

        return $this->redirectToRoute('da_groups_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $group The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Group $group)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('da_groups_delete', array('id' => $group->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}