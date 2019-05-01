<?php


namespace EcoBundle\Controller\API;

use EcoBundle\Entity\CategoriePub;
use EcoBundle\Entity\Group;
use EcoBundle\Entity\PublicationForum;
use EcoBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
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
     * @Route("/afficheAllHost", name="host_api_show")
     * @Method({"GET", "POST"})
     */
    public function allHostsAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('EcoBundle:Host')
            ->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        dump($tasks);
        return new JsonResponse($formatted);

    }
//
//    public function allHostRatingsAction()
//    {
//        $tasks = $this->getDoctrine()->getManager()
//            ->getRepository('EcoBundle:Hostrating')
//            ->findAll();
//
//        $serializer = new Serializer([new ObjectNormalizer()]);
//        $formatted = $serializer->normalize($tasks);
//        return new JsonResponse($formatted);
//
//    }
//
//    public function allHostParticipantsAction()
//    {
//        $tasks = $this->getDoctrine()->getManager()
//            ->getRepository('EcoBundle:Hostparticipation')
//            ->findAll();
//
//        $serializer = new Serializer([new ObjectNormalizer()]);
//        $formatted = $serializer->normalize($tasks);
//        return new JsonResponse($formatted);
//
//    }
//
//
//
//
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