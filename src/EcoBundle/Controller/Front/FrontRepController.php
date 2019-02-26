<?php
/**
 * Created by PhpStorm.
 * User: actar
 * Date: 18/02/2019
 * Time: 17:27
 */

namespace EcoBundle\Controller\Front;


use EcoBundle\Entity\DemeandeC;
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


        return $this->render('@Eco/Front/Reparateur/mainindex.html.twig');

    }

    /**
     * @Route("/repprof", name="reparateur_prof")
     * @Method({"GET", "POST"})
     */
    public function profAction(Request $request)
    {
        $demande = new DemeandeC();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $formAnnonce = $this->createForm('EcoBundle\Form\DemeandeCType', $demande);
        $formAnnonce->handleRequest($request);
        if ($formAnnonce->isSubmitted() && $formAnnonce->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $demande->setReparateur($user);
            $em->persist($demande);
            $em->flush();
            return $this->redirectToRoute('reparateur_shlista');
        }


        return $this->render('@Eco/Front/Reparateur/devenirrepprof.html.twig', array('formAnnonce' => $formAnnonce->createView()
        ));

    }

    /**
     * @Route("/copy", name="reparateur_copy")
     * @Method({"GET", "POST"})
     */
    public function ajaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        if ($request->get('type') == "Titre") {
            if ($request->get('valeur') == '') {
                $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findAll();
            } else {
                $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findBy(['titre' => $request->get('valeur')]);
            }


        } else if ($request->get('type') == "Utilisateur") {
            if ($request->get('valeur') == '') {
                $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findAll();
            } else {
                $user = $em->getRepository('EcoBundle:User')->findBy(['nom' => $request->get('valeur')]);

                $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findBy(['utilisateur' => $user]);
            }

        } else if ($request->get('type') == "Téléphone") {
            $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findBy(['categorie' => $request->get('type')]);

        } else if ($request->get('type') == "Electroménager") {
            $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findBy(['categorie' => $request->get('type')]);

        } else if ($request->get('type') == "Meuble") {
            $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findBy(['categorie' => $request->get('type')]);

        } else {
            $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findAll();
        }


        $template = $this->render('@Eco/Front/Reparateur/copy.html.twig', array(
            'annonce' => $annoncerep))->getContent();

        $json = json_encode($template);
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Route("/copy2", name="reparateur_copy2")
     * @Method({"GET", "POST"})
     */
    public function ajax2Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        if ($request->get('type') == "Nom") {
            if ($request->get('valeur') == '') {
                $reparation = $em->getRepository('EcoBundle:Reparateur')->findAll();
            } else {
                $reparation = $em->getRepository('EcoBundle:Reparateur')->findBy(['nom' => $request->get('valeur')]);
            }


        } else if ($request->get('type') == "Catégorie") {
            if ($request->get('valeur') == '') {
                $reparation = $em->getRepository('EcoBundle:Reparateur')->findAll();
            } else {
                $reparation = $em->getRepository('EcoBundle:Reparateur')->findBy(['specialite' => $request->get('valeur')]);
            }

        } else if ($request->get('type') == "Professionel") {
            $reparation = $em->getRepository('EcoBundle:Reparateur')->findBy(['type' => $request->get('type')]);

        } else if ($request->get('type') == "Normal") {
            $reparation = $em->getRepository('EcoBundle:Reparateur')->findBy(['type' => $request->get('type')]);

        } else if ($request->get('type') == "Tout") {
            $reparation = $em->getRepository('EcoBundle:Reparateur')->findAll();

        }


        $template = $this->render('@Eco/Front/Reparateur/copy2.html.twig', array(
            'reparateur' => $reparation))->getContent();

        $json1 = json_encode($template);
        $response1 = new Response($json1, 200);
        $response1->headers->set('Content-Type', 'application/json');
        return $response1;

    }

    /**
     * @Route("/shlist", name="reparateur_shlista")
     * @Method({"GET", "POST"})
     */
    public function annlistAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $annoncerep = $em->getRepository('EcoBundle:AnnonceRep')->findAll();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $annonce = new AnnonceRep();
        $annoncerep = array_reverse($annoncerep);

        $formAnnonce = $this->createForm('EcoBundle\Form\AnnonceRepType', $annonce);
        $formAnnonce->handleRequest($request);
        if ($formAnnonce->isSubmitted() && $formAnnonce->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $annonce->setUtilisateur($user);
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('reparateur_shlista');
        }
        return $this->render('@Eco/Front/Reparateur/shlistannoncerep.html.twig', array(
            'annonce' => $annoncerep, 'formAnnonce' => $formAnnonce->createView()
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


        return $this->render('@Eco/Front/Reparateur/shlistrep.html.twig', array(
            'reparateur' => $reparateur));


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

        $formAnnonce = $this->createForm('EcoBundle\Form\AnnonceRepType', $annonce);
        $formAnnonce->handleRequest($request);
        if ($formAnnonce->isSubmitted() && $formAnnonce->isValid()) {
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
        $em = $this->getDoctrine()->getManager();
        $announce = $em->getRepository(AnnonceRep::class)->find($request->get('id'));
        if ($request->isMethod('GET')) {
//update our object given the sent data in the request
            $announce->setLastprix($request->get('prix'));
            $announce->setReparateur($user);


//fresh the data base
            $em->flush();
//Redirect to the read
            return $this->redirectToRoute('reparateur_shlista');
        }

    }
}