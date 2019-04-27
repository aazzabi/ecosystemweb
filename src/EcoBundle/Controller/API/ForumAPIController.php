<?php
/**
 * Created by PhpStorm.
 * User: arafe
 * Date: 26/04/2019
 * Time: 14:44
 */

namespace EcoBundle\Controller\API;

use EcoBundle\Entity\CategoriePub;
use EcoBundle\Entity\Group;
use EcoBundle\Entity\PublicationForum;
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
     * @Method("GET")
     */
    public function indexApiAction()
    {
        $pubs = $this->getDoctrine()->getManager()
                     ->getRepository(PublicationForum::class)
                     ->findAll();
//        dump($pubs);die;
        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($pubs, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/publication/{id}", name="forum_api_show")
     * @Method("GET")
     */
    public function showApiAction($id)
    {
        $pubs = $this->getDoctrine()->getManager()
                     ->getRepository(Group::class)
                     ->find($id);

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($pubs, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/new", name="forum_api_new")
     * @Method({"GET", "POST"})
     */
    public function newApiAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $publication = new PublicationForum();
        $publication->setTitre($request->get('titre'));
        $publication->setDescription($request->get('description'));

        $categorie = $em->getRepository(CategoriePub::class)->find($request->get('categorie'));
        $user = $em->getRepository(User::class)->find($request->get('publicationCreatedBy'));

        $publication->setCategorie($categorie);
        $publication->setPublicationCreatedBy($user);

        $em->persist($publication);
        $em->flush();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($publication, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}