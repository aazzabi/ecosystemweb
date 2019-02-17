<?php

namespace EcoBundle\Controller\DashboardAdmin;

use EcoBundle\Entity\CategoriePub;
use EcoBundle\Entity\Livreur;
use EcoBundle\Entity\Reparateur;
use EcoBundle\Entity\RespAsso;
use EcoBundle\Entity\RespSoc;
use EcoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @Route("da")
 */
class DAForumController extends Controller
{
    /**
     *
     * @Route("/forum", name="da_forum_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        $categoriesPub = $this->getDoctrine()->getManager()->getRepository('EcoBundle:CategoriePub')->findAll();

        return $this->render('@Eco/DashboardAdmin/Forum/index.html.twig', array(
            'categories' => $categoriesPub,
        ));
    }

    /**
     *
     * @Route("/forum/new", name="da_forum_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $categoriePub = new CategoriePub();
        $formCategorie = $this->createForm('EcoBundle\Form\CategoriePubType', $categoriePub);
        $formCategorie->handleRequest($request);

        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoriePub);
            $em->flush();

            return $this->redirectToRoute('da_forum_index');
        }

        return $this->render('@Eco/DashboardAdmin/Forum/new.html.twig', array(
            'group' => $categoriePub,
            'formCategorie' => $formCategorie->createView(),
        ));
    }
}