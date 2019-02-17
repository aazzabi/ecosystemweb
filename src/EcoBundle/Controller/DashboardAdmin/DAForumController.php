<?php

namespace EcoBundle\Controller\DashboardAdmin;

use EcoBundle\Entity\CategoriePub;
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
class DAForumController extends Controller
{
    /**
     *
     * @Route("/forum", name="da_forum_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        $categoriesPub = $this->getDoctrine()->getManager()->getRepository('EcoBundle:CategoriePub')->findAll();

        return $this->render('@Eco/DashboardAdmin/Forum/index.html.twig', array(
            'categories' => $categoriesPub,
        ));
    }

    /**
     *
     * @Route("/forum/new", name="da_forum_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $categoriePub = new CategoriePub();
        $formCategorie = $this->createForm('EcoBundle\Form\CategoriePubType', $categoriePub);
        $formCategorie->handleRequest($request);

        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoriePub);
            $em->flush();

            return $this->redirectToRoute('da_forum_index');
        }

        return $this->render('@Eco/DashboardAdmin/Forum/new.html.twig', array(
            'group' => $categoriePub,
            'formCategorie' => $formCategorie->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/forum/categorie/{id}/edit", name="da_forum_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CategoriePub $categoriePub)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $editForm = $this->createForm('EcoBundle\Form\CategoriePubType', $categoriePub);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('da_forum_edit', array('id' => $categoriePub->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Forum/edit.html.twig', array(
            'categorie' => $categoriePub,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @Route("/forum/delete/{id}", name="da_categ_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteCategorieAction(Request $request, $id)
    {
        $categorie = $this->getDoctrine()->getRepository('EcoBundle:CategoriePub')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('da_forum_index');
    }
}