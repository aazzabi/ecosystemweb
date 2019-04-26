<?php
/**
 * Created by PhpStorm.
 * User: arafe
 * Date: 26/04/2019
 * Time: 14:44
 */

namespace EcoBundle\Controller\API;

use EcoBundle\Entity\PublicationForum;
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
     * @Route("/jms", name="jms")
     * @Method("GET")
     */
    public function jmsAction()
    {
        $pubs = $this->getDoctrine()->getManager()
                     ->getRepository(PublicationForum::class)
                     ->findAll();

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($pubs, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}