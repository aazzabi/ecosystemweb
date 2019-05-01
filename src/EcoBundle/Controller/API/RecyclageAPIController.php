<?php


namespace EcoBundle\Controller\API;


use EcoBundle\Entity\Host;
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
     * @Route("/addHost/{Name}/{Places}/{DateStart}/{DateEnd}/{Localisation}/{Participants}", name="addhost_api_show")
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
       // $Host->setOwnerid($OwnerID);
       $OwnerID = null;
        $Participants = null;
        //$Host->setParticipants($Participants);
        $Host->setLocalisation($Localisation);


        $em->persist($Host);
        $em->flush();

        return new JsonResponse("ok");
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
     * @Route("/ModifyHost/{id}/{owner}/{DateStart}/{DateEnd}/{Localisation}", name="host_api_modify")
     * @Method({"GET", "POST"})
     */
    public function ModifyHostAction($id, $Name, $DateStart, $DateEnd, $Localisation,Request $request){
        $Host = $this->getDoctrine()->getRepository('EcoBundle:Host')->findOneBy(array('id' => $id));
        dump($Host);
        $Host->setOwner($Name);
        $Host->setDatestart(new \DateTime ($DateStart));
        $Host->setDateend(new \DateTime ($DateEnd));
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




//    public function deleteApiAction($id)
//    {
//        $host = $this->getDoctrine()->getManager()
//            ->getRepository(Host::class)
//            ->find($id);
//        $em = $this->getDoctrine()->getManager();
//        $em->remove($host);
//        $em->flush();
//        $serializer = $this->get('jms_serializer');
//
//        $response = new Response($serializer->serialize($host, 'json'));
//        $response->headers->set('Content-Type', 'application/json');
//
//        return $response;
//    }
//
//    /**
//     * @Route("/new", name="host_api_new")
//     * @Method({"GET", "POST"})
//     */
//    public function newApiAction(Request $request)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $host = new Host();
//
//        $host->setTitre($request->get('titre'));
//        $host->setDescription($request->get('description'));
//
//        $categorie = $em->getRepository(CategoriePub::class)->find($request->get('categorie'));
//        $user = $em->getRepository(User::class)->find($request->get('publicationCreatedBy'));
//
//        $host->setCategorie($categorie);
//        $host->setPublicationCreatedBy($user);
//
//        $em->persist($host);
//        $em->flush();
//
//        $serializer = $this->get('jms_serializer');
//        $response = new Response($serializer->serialize($host, 'json'));
//        $response->headers->set('Content-Type', 'application/json');
//
//        return $response;
//    }
//
//
////
////
//
//
//

//    public function allRestoAction()
//    { $tasks = $this->getDoctrine()->getManager()
//        ->getRepository('GuideBundle:Divertissement')
//        ->findAll();
//
//        $serializer = new Serializer([new ObjectNormalizer()]);
//        $formatted = $serializer->normalize($tasks);
//        return new JsonResponse($formatted);
//    }
//    public function ApprouverCovoiturageAction($id,$iduser,$nbplace,$name,Request $request)
//    {   $em = $this->getDoctrine()->getEntityManager();
//        $objet = $em->getRepository(Covoiturage::class)->findOneBy(array('id'=>$id));
//        $v = new Passager();
//        $v->setIdConv($id);
//        $v->setIduser($iduser);
//        $v->setNbPlace($nbplace);
//        $v->setnameuser($name);
//        $objet->setnbPlaceRestantes($objet->getnbPlaceRestantes() - $request->get("nbplace"));
//
//        $em = $this->getDoctrine()->getEntityManager();
//        $em->persist($v);
//        $em->persist($objet);
//        $em->flush();
//
//        return new JsonResponse("ok");

//}






}