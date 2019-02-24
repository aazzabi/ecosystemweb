<?php
/**
 * Created by PhpStorm.
 * User: arafe
 * Date: 22/02/2019
 * Time: 21:46
 */

namespace EcoBundle\Controller\Front;

use EcoBundle\Entity\CategoriePub;
use EcoBundle\Entity\CommentairePublication;
use EcoBundle\Entity\Livreur;
use EcoBundle\Entity\PublicationForum;
use EcoBundle\Entity\Reparateur;
use EcoBundle\Entity\RespAsso;
use EcoBundle\Entity\RespSoc;
use EcoBundle\Entity\Signalisation;
use EcoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @Route("forum")
 */
class ForumController extends Controller
{
    /**
     *
     * @Route("/", name="front_forum_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $categoriesPub = $this->getDoctrine()->getManager()->getRepository('EcoBundle:CategoriePub')->findAll();
        $publicationsPubliee = $this->getDoctrine()->getManager()->getRepository('EcoBundle:PublicationForum')->findPublicationByEtat('publié');
        $publicationsArchivee = $this->getDoctrine()->getManager()->getRepository('EcoBundle:PublicationForum')->findPublicationByEtat('archivé');

        return $this->render('@Eco/Front/Forum/index.html.twig', array(
            'categories' => $categoriesPub,
            'publicationsPubliees' => $publicationsPubliee,
            'publicationsArchivees' => $publicationsArchivee,
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/publication/{id}", name="front_forum_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, PublicationForum $publicationForum)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $commentaire = new CommentairePublication();
        $commentaireForm = $this->createForm('EcoBundle\Form\CommentairePublicationType', $commentaire);
        $commentaireForm->handleRequest($request);

        $commentaire->setCommentedBy($user);
        $commentaire->setPublication($publicationForum);

        if ($commentaireForm->isSubmitted() && $commentaireForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('front_forum_show', array('id' => $publicationForum->getId()));
        }
        return $this->render('@Eco/Front/Forum/show.html.twig', array(
            'commentaireForm' => $commentaireForm->createView(),
            'publication' => $publicationForum,
        ));
    }

    /**
     *
     * @Route("/new", name="front_forum_new")
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

            return $this->redirectToRoute('front_forum_index');
        }

        return $this->render('@Eco/Front/Forum/new.html.twig', array(
            'publication' => $publication,
            'formPublication' => $formPublication->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/publication/{id}/edit", name="front_forum_edit")
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

        return $this->render('@Eco/Front/Forum/edit.html.twig', array(
            'publication' => $publication,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @Route("/publication/delete/{id}", name="front_forum_publication_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deletePublicationAction(Request $request, $id)
    {
        $publication = $this->getDoctrine()->getRepository('EcoBundle:PublicationForum')->find($id);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($publication->getPublicationCreatedBy() != $user) {
            throw new AccessDeniedException("Vous ne pouvez pas supprimer cette publication !", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute('front_forum_index');
    }
    /**
     * @Route("/commentaire/delete/{id}", name="front_forum_commentaire_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteCommentaireAction(Request $request, $id)
    {
        $commentaire = $this->getDoctrine()->getRepository('EcoBundle:CommentairePublication')->find($id);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($commentaire->getCommentedBy() != $user) {
            throw new AccessDeniedException("Vous ne pouvez pas supprimer ce commentaire !", Response::HTTP_FORBIDDEN);
        }
        $publicationForum = $commentaire->getPublication();
        $em = $this->getDoctrine()->getManager();
        $em->remove($commentaire);
        $em->flush();
        return $this->redirectToRoute('front_forum_show', array('id' => $publicationForum->getId()));
    }


    /**
     * @Route("/archiverPublication/{id}", name="front_forum_publication_archive")
     */
    public function archvierPublicationAction(PublicationForum $publicationForum)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($publicationForum->getPublicationCreatedBy() != $user) {
            throw new AccessDeniedException("Vous ne pouvez pas archiver cette publication !", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();
        $publicationForum->setEtat("archivé");
        $em->flush();
        return $this->redirectToRoute('front_forum_show', array('id' => $publicationForum->getId()));
    }
    /**
     * @Route("/desarchiverPublication/{id}", name="front_forum_publication_desarchive")
     */
    public function desarchvierPublicationAction(PublicationForum $publicationForum)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($publicationForum->getPublicationCreatedBy() != $user) {
            throw new AccessDeniedException("Vous ne pouvez pas désarchiver cette publication !", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();
        $publicationForum->setEtat("publié");
        $em->flush();
        return $this->redirectToRoute('front_forum_show', array('id' => $publicationForum->getId()));
    }

    /**
     * @Route("/signalerCommentaire/{id}", name="front_forum_signaler_commentaire")
     * @Method({"GET", "POST"})
     */
    public function signalerCommentaireAction(Request $request, $id)
    {
        $signalisation = new Signalisation();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $commentaire = $this->getDoctrine()->getRepository('EcoBundle:CommentairePublication')->find($id);
        $publicationForum= $commentaire->getPublication();

        $libRadio = $request->get('radioLib');
        $signalisation->setLibelle($libRadio);
        $signalisation->setSignaledBy($user);
        $signalisation->setCommentaire($commentaire);

        $em->persist($signalisation);
        $em->flush();
        return $this->redirectToRoute('front_forum_show', array('id' => $publicationForum->getId()));
    }

    /**
     *
     * @Route("/like/new", name="front_forum_comment_like")
     * @Method({"GET", "POST"})
     */
    public function commentLikeAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('EcoBundle:CommentairePublication')->find($id);
        $publicationForum = $commentaire->getPublication();
        if (($request->isXmlHttpRequest()))
        {
            $commentaire->setLikes($commentaire->getLikes()+1);
            $em->persist($commentaire);
            $em->flush();
            $arrData = ['likes' => $commentaire->getLikes()];
            return new JsonResponse($arrData);
        }
        return $this->redirectToRoute('front_forum_show', array('id' => $publicationForum->getId()));

    }
    /**
     *
     * @Route("/dislike/new", name="front_forum_comment_dislike")
     * @Method({"GET", "POST"})
     */
    public function commentDislikeAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('EcoBundle:CommentairePublication')->find($id);
        $publicationForum = $commentaire->getPublication();
        if (($request->isXmlHttpRequest()))
        {
            $commentaire->setDislikes($commentaire->getdislikes()+1);
            $em->persist($commentaire);
            $em->flush();
            $arrData = ['dislikes' => $commentaire->getDislikes()];
            return new JsonResponse($arrData);
        }
        return $this->redirectToRoute('front_forum_show', array('id' => $publicationForum->getId()));
    }
}