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
use EcoBundle\Entity\Reparateur;
use EcoBundle\Entity\User;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
 * @Route("userapi")
 */
class UserAPIController extends Controller
{
    /**
     * @Route("/getuserid/{id}", name="user_api_getuserdata")
     * @Method({"GET", "POST"})
     */
    public function getUserByIdAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('EcoBundle:User')->findOneBy(array('id' => $id));
        $serializer = new Serializer([new ObjectNormalizer()]);
        return new JsonResponse($serializer->normalize($user));
    }

    /**
     * @Route("/getttrep", name="user_api_gettoutrep")
     * @Method({"GET", "POST"})
     */
    public function getTtRepAction(Request $request){
        $task = $this->getDoctrine()->getManager()
            ->getRepository(Reparateur::class)
            ->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/checklogindata/username/{username}/password/{password}", name="user_api_checklogin")
     * @Method({"GET", "POST"})
     */
    public function getUserByUsernamePasswordAction(Request $request,$username,$password){
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('EcoBundle:User')->findBy(array('username'=> $username));
        $user = $users[0];


        if($user == null) return new JsonResponse(null);
        else{
            $passwordMatches = password_verify($password,$user->getPassword());
            if(!$passwordMatches) return new JsonResponse(null);
            else{
                $serializer = new Serializer([new ObjectNormalizer()]);
                return new JsonResponse($serializer->normalize($user));
            }
        }
    }


}