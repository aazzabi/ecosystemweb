<?php

namespace EcoBundle\Controller\DashboardAdmin;

use EcoBundle\Entity\CategoriePub;
use EcoBundle\Entity\Livreur;
use EcoBundle\Entity\PublicationForum;
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
        $publications = $this->getDoctrine()->getManager()->getRepository('EcoBundle:PublicationForum')->findAll();

        return $this->render('@Eco/DashboardAdmin/Forum/index.html.twig', array(
            'categories' => $categoriesPub,
            'publications' => $publications,
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
     * Finds and displays a user entity.
     *
     * @Route("/forum/publication/{id}", name="da_forum_publication_show")
     * @Method("GET")
     */
    public function showAction(PublicationForum $publicationForum)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        return $this->render('@Eco/DashboardAdmin/Forum/show.html.twig', array(
            'publication' => $publicationForum,
        ));
    }
    /**
     * @Route("/forum/categorie/delete/{id}", name="da_categ_delete")
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
    /**
     * @Route("/forum/publication/delete/{id}", name="da_forum_publication_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deletePublicationAction(Request $request, $id)
    {
        $publication = $this->getDoctrine()->getRepository('EcoBundle:PublicationForum')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute('da_forum_index');
    }
}