<?php

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
 * @Route("eventApi")
 */

class EventAPIController extends Controller
{

    /**
     * @Route("/jmsevent", name="jms")
     * @Method({"GET", "POST"})
     */
    public function jmsEventAction()
    {
        /* $event = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($event);
         return new JsonResponse($formatted);*/


        $events = $this->getDoctrine()->getManager()
            ->getRepository(Evenement::class)
            ->findAll();



        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($events, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;


    }
}
