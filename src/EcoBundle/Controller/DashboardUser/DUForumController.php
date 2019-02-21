<?php
namespace EcoBundle\Controller\DashboardUser;

use EcoBundle\Entity\CategoriePub;
use EcoBundle\Entity\CommentairePublication;
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
 * @Route("du")
 */
class DUForumController extends Controller
{
    /**
     *
     * @Route("/forum", name="du_forum_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $publications = $this->getDoctrine()->getManager()->getRepository('EcoBundle:PublicationForum')
            ->findBy(array('publicationCreatedBy'=> $user));
        return $this->render('@Eco/DashboardUser/Forum/index.html.twig', array(
            'publications' => $publications,
        ));
    }

    /**
     *
     * @Route("/forum/new", name="du_forum_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $publication = new PublicationForum();
        $formPublication = $this->createForm('EcoBundle\Form\PublicationForumType', $publication);
        $formPublication->handleRequest($request);

        $publication->setEtat('publié');
        $publication->setPublicationCreatedBy($user);

        if ($formPublication->isSubmitted() && $formPublication->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush();

            return $this->redirectToRoute('du_forum_index');
        }

        return $this->render('@Eco/DashboardUser/Forum/new.html.twig', array(
            'publication' => $publication,
            'formPublication' => $formPublication->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/forum/publication/{id}/edit", name="du_forum_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PublicationForum $publication)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($publication->getPublicationCreatedBy() != $user) {
            throw new AccessDeniedException("Vous ne pouvez pas modifier cette publication !", Response::HTTP_FORBIDDEN);
        }
        $editForm = $this->createForm('EcoBundle\Form\PublicationForumType', $publication);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('du_forum_edit', array('id' => $publication->getId()));
        }

        return $this->render('@Eco/DashboardUser/Forum/edit.html.twig', array(
            'publication' => $publication,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/forum/publication/{id}", name="du_forum_publication_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, PublicationForum $publication)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $commentaire = new CommentairePublication();
        if ($publication->getPublicationCreatedBy() != $user) {
            throw new AccessDeniedException("Vous ne pouvez pas modifier cette publication !", Response::HTTP_FORBIDDEN);
        }
        $commentaireForm = $this->createForm('EcoBundle\Form\CommentairePublicationType', $commentaire);
        $commentaireForm->handleRequest($request);

        $commentaire->setCommentedBy($user);
        $commentaire->setPublication($publication);

        if ($commentaireForm->isSubmitted() && $commentaireForm->isValid()) {
            $com = $request->get('ecobundle_commentairepublication[commentairePhoto][file]');
//            var_dump($com);die;
//            var_dump($commentaireForm->getNormData()->);die;
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('du_forum_publication_show', array('id' => $publication->getId()));
        }
        return $this->render('@Eco/DashboardUser/Forum/show.html.twig', array(
            'publication' => $publication,
            'commentaireForm' => $commentaireForm->createView(),
        ));
    }

    /**
     * @Route("/foruum/archiverPublication/{id}", name="du_forum_publication_archive")
     */
    public function archvierPublicationAction(PublicationForum $publicationForum)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();
        $publicationForum->setEtat("archivé");
        $em->flush();
        return $this->redirectToRoute('du_forum_index');

    }
    /**
     * @Route("/foruum/desarchiverPublication/{id}", name="du_forum_publication_desarchive")
     */
    public function desarchvierPublicationAction(PublicationForum $publicationForum)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();
        $publicationForum->setEtat("publié");
        $em->flush();
        return $this->redirectToRoute('du_forum_publication_show', array('id' => $publicationForum->getId()));
    }

    /**
     * @Route("/forum/publication/delete/{id}", name="du_forum_publication_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deletePublicationAction(Request $request, $id)
    {
        $publication = $this->getDoctrine()->getRepository('EcoBundle:PublicationForum')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute('du_forum_index');
    }
    /**
     * @Route("/forum/commentaire/delete/{id}", name="du_commentaire_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteCommentaireAction(Request $request, $id)
    {
        $commentaire = $this->getDoctrine()->getRepository('EcoBundle:CommentairePublication')->find($id);
        $publicationForum = $commentaire->getPublication();
        $em = $this->getDoctrine()->getManager();
        $em->remove($commentaire);
        $em->flush();
        return $this->redirectToRoute('du_forum_publication_show', array('id' => $publicationForum->getId()));
    }
}