<?php
/**
 * Created by PhpStorm.
 * User: arafe
 * Date: 26/04/2019
 * Time: 14:44
 */

namespace EcoBundle\Controller\API;

use EcoBundle\Entity\CategoriePub;
use EcoBundle\Entity\CommentairePublication;
use EcoBundle\Entity\Group;
use EcoBundle\Entity\PublicationForum;
use EcoBundle\Entity\SignalisationForumComm;
use EcoBundle\Entity\User;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 *
 * @Route("forumApi")
 */
class ForumAPIController extends Controller
{
    /**
     * @Route("/", name="forum_api_index")
     * @Method({"GET", "POST"})
     */
    public function indexApiAction()
    {
        $pubs = $this->getDoctrine()->getManager()
                     ->getRepository(PublicationForum::class)
                     ->findAll();
        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($pubs, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    /**
     * @Route("/allCategories", name="forum_api_all_categories")
     * @Method({"GET", "POST"})
     */
    public function allCategoriesApiAction()
    {
        $categories = $this->getDoctrine()->getManager()
                     ->getRepository(CategoriePub::class)
                     ->findAll();
        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($categories, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/allComments/{idPub}", name="forum_api_all_comment_pub")
     * @Method({"GET", "POST"})
     */
    public function allCommentsByPublicationApiAction($idPub)
    {

        $p = $this->getDoctrine()->getManager()
                     ->getRepository(PublicationForum::class)
                     ->find($idPub);
        $commentaire = $this->getDoctrine()->getManager()
                     ->getRepository(CommentairePublication::class)
                     ->findBy(array('publication'=> $p));
        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($commentaire, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/publication/{id}", name="forum_api_show")
     * @Method({"GET", "POST"})
     */
    public function showApiAction($id)
    {
        $pubs = $this->getDoctrine()->getManager()
                     ->getRepository(PublicationForum::class)
                     ->find($id);

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($pubs, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/publication/delete/{id}", name="forum_api_delete")
     * @Method("GET")
     */
    public function deleteApiAction($id)
    {
        $publication = $this->getDoctrine()->getManager()
                     ->getRepository(PublicationForum::class)
                     ->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($publication);
        $em->flush();
        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($publication, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/new/titre/{titre}/description/{desc}/user/{userId}/categorie/{categ}", name="forum_api_new")
     * @Method({"GET", "POST"})
     */
    public function newApiAction(Request $request ,$titre, $desc, $userId, $categ)
    {
        $em = $this->getDoctrine()->getManager();

        $publication = new PublicationForum();
        $publication->setTitre($titre);
        $publication->setDescription($desc);

        $categorie = $em->getRepository(CategoriePub::class)->find($categ);
        $user = $em->getRepository(User::class)->find($userId);

        $publication->setCategorie($categorie);
        $publication->setPublicationCreatedBy($user);

        $em->persist($publication);
        $em->flush();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($publication, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/newComment/publication/{idPub}/content/{content}/user/{userId}", name="forum_api_new_comment")
     * @Method({"GET", "POST"})
     */
    public function newCommentApiAction(Request $request, $idPub, $content, $userId)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository(PublicationForum::class)->find($idPub);
        $user = $em->getRepository(User::class)->find($userId);

        $commentaire = new CommentairePublication();
        $commentaire->setDescription($content);
        $commentaire->setPublication($publication);
        $commentaire->setCommentedBy($user);

        $em->persist($commentaire);
        $em->flush();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($commentaire, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/like/commentaire/{idC}", name="forum_api_like_comment")
     * @Method({"GET", "POST"})
     */
    public function likeCommentApiAction($idC){
        $em = $this->getDoctrine()->getManager();
        $comm = $em->getRepository(CommentairePublication::class)->find($idC);
        $comm->setLikes($comm->getLikes()+1);

        $em->persist($comm);
        $em->flush();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($comm, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     * @Route("/dislike/commentaire/{idC}", name="forum_api_dislike_comment")
     * @Method({"GET", "POST"})
     */
    public function dislikeCommentApiAction($idC){
        $em = $this->getDoctrine()->getManager();
        $comm = $em->getRepository(CommentairePublication::class)->find($idC);
        $comm->setDislikes($comm->getDislikes()+1);

        $em->persist($comm);
        $em->flush();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($comm, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/getdislikes/{idC}", name="forum_api_get_comment_dislikes")
     * @Method({"GET", "POST"})
     */
    public function getCommentDislikesApiAction($idC){
        $em = $this->getDoctrine()->getManager();
        $comm = $em->getRepository(CommentairePublication::class)->find($idC);
        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($comm->getDislikes(), 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     * @Route("/getlikes/{idC}", name="forum_api_get_comment_likes")
     * @Method({"GET", "POST"})
     */
    public function getCommentLikesApiAction($idC){
        $em = $this->getDoctrine()->getManager();
        $comm = $em->getRepository(CommentairePublication::class)->find($idC);
        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($comm->getLikes(), 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/signaler/commentaire/{idC}/libelle/{lib}/by/{userId}", name="forum_api_signaler_comment")
     * @Method({"GET", "POST"})
     */
    public function signalerCommenApiAction($lib ,$userId, $idC){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($userId);
        $comm = $em->getRepository(CommentairePublication::class)->find($idC);
        $s = new SignalisationForumComm();
        $s->setLibelle($lib);
        $s->setSignaledBy($user);
        $s->setCommentaire($comm);
        $s->setLibelle($lib);
        $em->persist($s);
        $em->flush();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($s, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}