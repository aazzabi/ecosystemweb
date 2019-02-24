<?php

namespace EcoBundle\Controller\DashboardAdmin;

use EcoBundle\Entity\PtCollecte;
use EcoBundle\Entity\Livreur;
use EcoBundle\Entity\Reparateur;
use EcoBundle\Entity\RespAsso;
use EcoBundle\Entity\RespSoc;
use EcoBundle\Entity\Mission;
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
class DARecyclageController extends Controller
{
    /**
     * @Route("/recyclage", name="da_recyclage_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();

        $recys = $em->getRepository('EcoBundle:PtCollecte')->findAll();
        $missions = $em->getRepository('EcoBundle:Mission')->findAll();
        $categoriesEvts = $em->getRepository('EcoBundle:CategorieEvts')->findAll();

        return $this->render('@Eco/DashboardAdmin/Recyclage/index.html.twig', array(
            'recys' => $recys,
            'missions' => $missions,
            'categoriesEvts' => $categoriesEvts


        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/recyclage/new", name="da_recyclage_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $recys = new PtCollecte();
        $form = $this->createForm('EcoBundle\Form\PtCollecteType', $recys);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recys);
            $em->flush();

            return $this->redirectToRoute('da_recyclage_index', array('id' => $recys->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Recyclage/new.html.twig', array(
            'PtCollecte' => $recys,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a user entity.
     *
     * @Route("/recyclage/{id}", name="da_recyclage_show")
     * @Method("GET")
     */
    public function showAction(PtCollecte $PtCollecte)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $users= $PtCollecte->getResponsable();
        $deleteForm = $this->createDeleteForm($PtCollecte);

        return $this->render('@Eco/DashboardAdmin/Recyclage/show.html.twig', array(
            'users' => $users,
            'PtCollecte' => $PtCollecte,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * @Route("/recyclage/mission", name="da_mission_index")
     * @Method("GET")
     */
    public function indexAction2()
    {
        $em = $this->getDoctrine()->getManager();

        $recys = $em->getRepository('EcoBundle:PtCollecte')->findAll();
        $missions = $em->getRepository('EcoBundle:Mission')->findAll();

        return $this->render('@Eco/DashboardUser/Recyclage/missions.html.twig', array(
            'recys' => $recys,
            'missions' => $missions

        ));
    }
    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/recyclage/{id}/edit", name="da_recyclage_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PtCollecte $PtCollecte)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $deleteForm = $this->createDeleteForm($PtCollecte);
        $editForm = $this->createForm('EcoBundle\Form\UserType', $PtCollecte);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('da_recyclage_edit', array('id' => $PtCollecte->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Recyclage/edit.html.twig', array(
            'user' => $PtCollecte,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/user/{id}", name="da_recyclage_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PtCollecte $PtCollecte)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $form = $this->createDeleteForm($PtCollecte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($PtCollecte);
            $em->flush();
        }

        return $this->redirectToRoute('da_recyclage_delete');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $PtCollecte The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PtCollecte $PtCollecte)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('da_recyclage_delete', array('id' => $PtCollecte->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}