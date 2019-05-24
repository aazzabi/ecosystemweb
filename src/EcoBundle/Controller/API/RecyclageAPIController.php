<?php


namespace EcoBundle\Controller\API;


use EcoBundle\Entity\Host;
use EcoBundle\Entity\Hostrating;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 *
 * @Route("/host/mobile")
 */
class RecyclageAPIController extends Controller
{
    /**
     * @Route("/HostIndex", name="recyclage_api_index")
     * @Method("GET")
     */
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * @Route("/afficheAllMission", name="recyclage_api_show")
     * @Method({"GET", "POST"})
     */
    public function allMissionsAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('EcoBundle:Missions')
            ->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/afficheAllHost", name="hosts_api_show")
     * @Method({"GET", "POST"})
     */
    public function allHostsAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository(Host::class)
            ->findAll();

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($tasks, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
    /**
     * @Route("/afficheAllHostRating", name="hostrating_api_show")
     * @Method({"GET", "POST"})
     */
    public function allHostRatingsAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('EcoBundle:Hostrating')
            ->findAll();

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($tasks, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
    /**
     * @Route("/afficheAllHostParticipant", name="hostparticipant_api_show")
     * @Method({"GET", "POST"})
     */
    public function allHostParticipantsAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('EcoBundle:Hostparticipation')
            ->findAll();

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($tasks, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
    //-----------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------

    /**
     * @Route("/addHost/{Name}/{Places}/{DateStart}/{DateEnd}/{Localisation}/{OwnerID}/{Participants}", name="addhost_api_show")
     * @Method({"GET", "POST"})
     */
    public function addHostAction($Name, $Places, $DateStart, $DateEnd, $Localisation, $OwnerID, $Participants,Request $request)
    {   $em = $this->getDoctrine()->getEntityManager();
        $Host = new Host();
        $Host->setOwner($Name);
        $Host->setAvailableplaces($Places);
        $Host->setTotalplaces($Places);
        $Host->setDatestart(new \DateTime ($DateStart));
        $Host->setDateend(new \DateTime ($DateEnd));
        $Host->setOwnerid($OwnerID);
        $Host->setParticipants($Participants);
        $Host->setLocalisation($Localisation);

        dump($Host);
        $em->persist($Host);
        $em->flush();
    dump($Host);
        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($Host, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
       // return new JsonResponse("ok");
    }

    /**
     * @Route("/DisplayHost/{id}", name="host_api_show")
     * @Method({"GET", "POST"})
     */
    public function getHostAction($id)
    {
        $host = $this->getDoctrine()->getManager()
            ->getRepository(Host::class)
            ->find($id);

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($host, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     * @Route("/DisplayHostRating/{HostId}", name="hostR_api_show")
     * @Method({"GET", "POST"})
     */
    public function getHostRatingAction($HostId)
    {
        $host = $this->getDoctrine()->getManager()
            ->getRepository(Hostrating::class)
            ->find($HostId);

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($host, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     * @Route("/ModifyHost/{Name}/{Places}/{DateStart}/{DateEnd}/{Localisation}/{OwnerID}/{Participants}", name="host_api_modify")
     * @Method({"GET", "POST"})
     */
    public function ModifyHostAction($id, $Name, $DateStart, $DateEnd, $Localisation,$OwnerID,$Participants, Request $request){
        $Host = $this->getDoctrine()->getRepository('EcoBundle:Host')->findOneBy(array('id' => $id));
        dump($Host);
        $Host->setOwner($Name);
        $Host->setDatestart(new \DateTime ($DateStart));
        $Host->setDateend(new \DateTime ($DateEnd));
        $Host->setLocalisation($Localisation);
        $Host->setOwnerid($OwnerID);
        $Host->setParticipants($Participants);
        $Host->setLocalisation($Localisation);

        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse("ok");
    }

    /**
     * @Route("/DeleteHost/{id}", name="host_api_delete")
     * @Method({"GET", "POST"})
     */
    public function DeleteHostAction($id){
        $host = $this->getDoctrine()->getManager()
            ->getRepository(Host::class)
            ->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($host);
        $em->flush();
        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($host, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }










}