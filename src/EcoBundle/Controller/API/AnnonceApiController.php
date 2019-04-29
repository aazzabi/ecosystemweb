<?php

namespace EcoBundle\Controller\API;

use EcoBundle\Entity\Annonce;
use EcoBundle\Entity\CategorieAnnonce;
use EcoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @Route("annonceApi")
 */
class AnnonceApiController extends Controller
{
    /**
     * @Route("/", name="getAll_annonce")
     */
    public function getall()
    {
        $task = $this->getDoctrine()->getManager()
            ->getRepository(Annonce::class)
            ->findAll();
        $serializer = $this->get('jms_serializer');
        $response = new Response(
            $serializer->serialize(
                $task,
                'json'
            )
        );
        $response->headers->set('Content-Type', 'application/json');

        return $response;


    }

    /**
     * @Route("/categories") , name="Categorie_api_all")
     */
    public function getAllCategorieAction()
    {
        $cat = $this->getDoctrine()->getManager()->getRepository(CategorieAnnonce::class)->findAll();
        $serializer = $this->get('jms_serializer');

        $response = new Response(
            $serializer->serialize(
                $cat,
                'json'
            )
        );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     * @Route("/annonce/{text}", name="annonceBytext")
     */
    public function getAnnonceAction($text)
    {
        $ann = $this->getDoctrine()->getManager()
            ->getRepository(Annonce::class)
            ->RechercheTitreAnnonce($text);
        //dump($ann);exit();
        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($ann, 'json'));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/new", name="annoce_api_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $annonce = new Annonce();
        $annonce->setTitre($request->get('titre'));
        $annonce->setDescription($request->get('description'));
        $annonce->setPrix($request->get('prix'));
        $annonce->setEtat('Disponible');
        $annonce->setRegion($request->get('region'));
        $annonce->setLikes(0);
        $annonce->setViews(0);
        $annonce->setPhoto($request->get('photo'));
        $categorie = $em->getRepository(CategorieAnnonce::class)->find($request->get('categorie'));
        $user = $em->getRepository(User::class)->find($request->get('user'));
        $annonce->setCategorie($categorie);
        $annonce->setUser($user);

        $em->persist($annonce);
        $em->flush();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($annonce, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
