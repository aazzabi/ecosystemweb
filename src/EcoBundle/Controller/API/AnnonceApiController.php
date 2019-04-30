<?php

namespace EcoBundle\Controller\API;

use EcoBundle\Entity\Annonce;
use EcoBundle\Entity\CategorieAnnonce;
use EcoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/categories") , name="Categorie_api_all")
     */
    public function getAllCategorieAction()
    {
        $cat = $this->getDoctrine()->getManager()->getRepository(CategorieAnnonce::class)->findAll();
        $serializer = $this->get('jms_serializer');

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($cat);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/annonce/{id}") , name="find_api_annonce")
     */
    public function getAnnonceById($id)
    {
        $ann = $this->getDoctrine()->getManager()->getRepository(Annonce::class)->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($ann);
        return new JsonResponse($formatted);
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

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($ann);
        return new JsonResponse($formatted);
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

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($annonce);
        return new JsonResponse($formatted);
    }
}
