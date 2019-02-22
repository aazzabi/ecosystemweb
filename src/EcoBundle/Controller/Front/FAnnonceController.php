<?php

namespace EcoBundle\Controller\Front;

use EcoBundle\Entity\Annonce;
use EcoBundle\Entity\MentionAnnonce;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("front")
 */
class FAnnonceController extends Controller
{
    /**
     * @Route("/annonce", name="f_annonce_index")
     * @Method("GET")
     */
    public function indexTestAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
        $annnonce = $em->getRepository('EcoBundle:Annonce')->findAll();
        $mention = $em->getRepository('EcoBundle:MentionAnnonce')->findAll();

        return $this->render('@Eco/Annonce/annonce.html.twig', array(
            "annonces"=>$annnonce,'categories'=> $categories,'mentions' =>$mention,
        ));
    }
    /**
     * Finds and displays a user entity.
     *
     * @Route("/annonce/{id}", name="f_annonce_show")
     * @Method("GET")
     */
    public function showAction(Annonce $annonce)
    {

        return $this->render('@Eco/Annonce/show.html.twig', array(
            'annonce' => $annonce,
        ));
    }
    /**
     * @Route("/annonce/categorie/{cat}", name="f_recherch_Categorie")
     * @Method("GET")
     */
    public function RecherchTestAction(Request $request,$cat)
    {

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
        $annnonce = new Annonce();
        $annnonce = $em->getRepository('EcoBundle:Annonce')->findByCategorie($cat);

        return $this->render('@Eco/Annonce/annonce.html.twig', array(
            "annonces"=>$annnonce,'categories'=> $categories,

        ));
    }
    /**
     * Creates a new Categorie et annonce entity.
     *
     * @Route("/annonce/jaime/new", name="f_annonce_jaime")
     * @Method({"GET", "POST"})
     */
    public function newJaimeAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $mention = new MentionAnnonce();

        if (($request->isXmlHttpRequest()))
        {

            $id = $request->get('Id');
            $em = $this->getDoctrine()->getManager();

            $annonce = $em->getRepository('EcoBundle:Annonce')->find($id);
            $mention->setAnnonce($annonce);
            $mention->setUser($user);
            $em->persist($mention);
            $em->flush();
            return $this->redirectToRoute('du_annonce_index');
        }
    }
    /**
     * @Route("/annonce/aa", name="test")
     * @Method("POST")
     */
    public function TrierAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();

        if (($request->isXmlHttpRequest())) {

            $val = $request->get('valeur');

            $serializer = new Serializer(array(new ObjectNormalizer()));
            $annonces = $em->getRepository('EcoBundle:Annonce')->trier();
            var_dump($annonces);die;
            $data = $serializer->normalize($annonces);
            return new JsonResponse($data);
        }

        return $this->render('@EspritParc/Voiture/recherche.html.twig', array(
            'categories'=>$categories));


    }
}
