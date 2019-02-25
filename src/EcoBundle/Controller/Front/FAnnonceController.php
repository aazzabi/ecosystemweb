<?php

namespace EcoBundle\Controller\Front;

use EcoBundle\Entity\Annonce;
use EcoBundle\Entity\MentionAnnonce;
use EcoBundle\Entity\SignalAnnonce;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("front")
 */
class FAnnonceController extends Controller
{
    /**
     * @Route("/annonce", name="f_annonce_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
        $annnonce = $em->getRepository('EcoBundle:Annonce')->findAll();
        $likes = $em->getRepository('EcoBundle:Annonce')->likeAnnonce();
        $signal = $em->getRepository('EcoBundle:SignalAnnonce')->findAll();
        return $this->render('@Eco/Annonce/annonce.html.twig', array(
            "annonces" => $annnonce, 'categories' => $categories, 'likes' => $likes, 'signal' => $signal,
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

        $annonce->setViews($annonce->getViews() + 1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->render('@Eco/Annonce/show.html.twig', array(
            'annonce' => $annonce,
        ));
    }

    /**
     * @Route("/annonce/categorie/{cat}", name="f_recherch_Categorie")
     * @Method("GET")
     */
    public function RecherchCategorieAction(Request $request, $cat)
    {

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
        $annnonce = new Annonce();
        $annnonce = $em->getRepository('EcoBundle:Annonce')->findByCategorie($cat);

        return $this->render('@Eco/Annonce/annonce.html.twig', array(
            "annonces" => $annnonce, 'categories' => $categories,

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

        if (($request->isXmlHttpRequest())) {
            $id = $request->get('Id');
            $em = $this->getDoctrine()->getManager();
            $annonce = $em->getRepository('EcoBundle:Annonce')->find($id);
            $annonce->setLikes($annonce->getLikes() + 1);
            $em->flush();
            return $this->redirectToRoute('du_annonce_index');
        }
    }

    /**
     * @Route("/trier/{val}", name="f_annonce_trier")
     * @Method("GET")
     */
    public function TrierAction(Request $request)
    {

        $val = $request->get('val');
        if ($val == 'PR') {
            $em = $this->getDoctrine()->getManager();
            $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
            $annonces = $em->getRepository('EcoBundle:Annonce')->trierPlusRecent();
            $likes = $em->getRepository('EcoBundle:Annonce')->likeAnnonce();

        } elseif ($val = 'PE') {
            $em = $this->getDoctrine()->getManager();
            $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
            $annonces = $em->getRepository('EcoBundle:Annonce')->trierPrixElv();
            $likes = $em->getRepository('EcoBundle:Annonce')->likeAnnonce();


        } else {
            $em = $this->getDoctrine()->getManager();
            $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
            $annonces = $em->getRepository('EcoBundle:Annonce')->trierPrixBas();
            $likes = $em->getRepository('EcoBundle:Annonce')->likeAnnonce();

        }
        return $this->render('@Eco/Annonce/annonce.html.twig', array(
            "annonces" => $annonces, 'categories' => $categories, 'likes' => $likes,
        ));
    }

    /**
     * @Route("/annonce/recherche)", name="f_recherch")
     * @Method("GET")
     */
    public function RecherchAction(Request $request)
    {

        if (($request->isXmlHttpRequest())) {
            $val = $request->get('val');
            dump($val);exit();
            $em = $this->getDoctrine()->getManager();
            $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
            $annnonce = $em->getRepository('EcoBundle:Annonce')->RechercheTitreAnnonce($val);
            $likes = $em->getRepository('EcoBundle:Annonce')->likeAnnonce();
            $signal = $em->getRepository('EcoBundle:SignalAnnonce')->findAll();
            return $this->render('@Eco/Annonce/Recherche.html.twig', array(
                "annonces" => $annnonce, 'categories' => $categories, 'likes' => $likes, 'signal' => $signal,
            ));
        }

    }




    /**
     * @Route("/pdf/{id}", name="pdf")
     */

    public function pdfAction(Request $request)
    {
        $val = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository('EcoBundle:Annonce')->find($val);

        $snappy = $this->get('knp_snappy.pdf');

        $html = $this->renderView('@Eco/Annonce/annoncePdf.html.twig', array(
            'annonce' => $annonce,
        ));

        $filename = 'Annonce';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }


}
