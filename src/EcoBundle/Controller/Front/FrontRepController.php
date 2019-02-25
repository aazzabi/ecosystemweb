<?php
/**
 * Created by PhpStorm.
 * User: actar
 * Date: 18/02/2019
 * Time: 17:27
 */

namespace EcoBundle\Controller\Front;


use EcoBundle\Entity\Group;
use EcoBundle\Entity\Livreur;
use EcoBundle\Entity\Reparateur;
use EcoBundle\Entity\RespAsso;
use EcoBundle\Entity\RespSoc;
use EcoBundle\Entity\AnnonceRep;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
/**
 *
 * @Route("reparateur")
 */
class FrontRepController extends Controller
{
    /**
     * @Route("/", name="reparateur_mainindex")
     * @Method("GET")
     */
    public function indexAction()
    {


        return $this->render('@Eco/Front/mainindex.html.twig');

    }
    /**
     * @Route("/copy", name="reparateur_copy")
     * @Method({"GET", "POST"})
     */
    public function copyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->get('type')=="Titre")
        {$annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findBy(['titre' => $request->get('valeur')]);}
        else  if ($request->get('type')=="Utilisateur"){
            $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findBy(['utilisateur' => $request->get('valeur')]);
        }


        $template = $this->render('@Eco/Front/test.html.twig', array(
            'annonce' => $annoncerep))->getContent();

        $json = json_encode($template);
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }
    /**
     * @Route("/shlist", name="reparateur_shlista")
     * @Method({"GET", "POST"})
     */
    public function annlistAction( Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findAll();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $annonce = new AnnonceRep();
        $annoncerep=array_reverse($annoncerep);

        $formAnnonce = $this->createForm('EcoBundle\Form\AnnonceRepType',$annonce);
        $formAnnonce->handleRequest($request);
        if($formAnnonce->isSubmitted() && $formAnnonce->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $annonce->setUtilisateur($user);
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('reparateur_shlista');
        }
        return $this->render('@Eco/Front/shlistannoncerep.html.twig', array(
            'annonce' => $annoncerep,'formAnnonce' => $formAnnonce->createView()
        ));


    }

    /**
     * @Route("/shrep", name="reparateur_shrep")
     * @Method({"GET"})
     */
    public function replistAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reparateur = $em->getRepository('EcoBundle:Reparateur')->findAll();


        return $this->render('@Eco/Front/shlistrep.html.twig', array(
            'reparateur' => $reparateur));


    }

    /**
     * @Route("/detailrep", name="reparateur_detailrep")
     * @Method({"GET"})
     */
    public function detailrepAction()
    {

        return $this->render('@Eco/Front/detailrep.html.twig');


    }

    /**
     * @Route("/ajax", name="reparateur_search")
     * @Method({"POST"})
     */
    public function ajaxAction(Request $request) {
        /* on récupère la valeur envoyée par la vue */

        $personnage = $request->request->get('id1');
//        $em = $this->getDoctrine()->getManager();
//        $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findAll();
echo $personnage;
        return $this->render('reparateur_copy');

    }



    /**
     * Creates a new annonceREp entity.
     *
     * @Route("/newanrep/new", name="reparateur_newanrep")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
      /*  if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }*/
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $annonce = new AnnonceRep();

        $formAnnonce = $this->createForm('EcoBundle\Form\AnnonceRepType',$annonce);
        $formAnnonce->handleRequest($request);
        if($formAnnonce->isSubmitted() && $formAnnonce->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $annonce->setUtilisateur($user);
            $em->persist($annonce);
            $em->flush();
        }

        return $this->render('@Eco/Front/addnewannrep.html.twig', array(
            'formAnnonce' => $formAnnonce->createView()
        ));

    }
    /**
     * Update  annonceREp entity.
     *
     * @Route("/updateanrep", name="reparateur_updateanrep")
     * @Method({"GET"})
     */

    public function updateAction(Request $request)
    {
        /*  if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
              throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
          }*/
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $announce = $em->getRepository(AnnonceRep::class)->find($request->get('id'));
        if ($request->isMethod('GET')) {
//update our object given the sent data in the request
            $announce->setLastprix($request->get('prix'));
            $announce->setReparateur("$user");


//fresh the data base
            $em->flush();
//Redirect to the read
            return $this->redirectToRoute('reparateur_shlista');
        }

    }
}