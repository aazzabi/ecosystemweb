<?php

namespace EcoBundle\Controller;

namespace EcoBundle\Controller\DashboardAdmin;

use EcoBundle\Entity\Annonce;
use EcoBundle\Entity\CategorieAnnonce;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @Route("da")
 */
class DAAnnonceControllerController extends Controller
{
    /**
     * @Route("/annonce", name="da_annonce_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();
        $categorieAnnonce = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
        $annonce = $em->getRepository('EcoBundle:Annonce')->findAll();
        return $this->render('@Eco/DashboardAdmin/Annonce/index.html.twig', array(
            'annonce' => $annonce,
            'categorieAnnonce' => $categorieAnnonce,
        ));
    }

    /**
     * Creates a new Categorie et annonce entity.
     *
     * @Route("/annonce/new", name="da_annonce_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $annonce = new Annonce();
        $categorieAnnonce = new CategorieAnnonce();
        $formCategorie = $this->createForm('EcoBundle\Form\CategorieAnnonceType', $categorieAnnonce);
        $formAnnonce = $this->createForm('EcoBundle\Form\AnnonceType',$annonce);
        $formCategorie->handleRequest($request);
        $formAnnonce->handleRequest($request);
        if($formAnnonce->isSubmitted() && $formAnnonce->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $annonce->setEtat("Disponible");
            $annonce->setUser($user);
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('da_annonce_index');
        }
        if ($formCategorie->isSubmitted() && $formCategorie->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorieAnnonce);
            $em->flush();
            return $this->redirectToRoute('da_annonce_index');
        }
        return $this->render('@Eco/DashboardAdmin/Annonce/new.html.twig', array(
            'formCatAnn' => $formCategorie->createView(),
            'formAnnonce' => $formAnnonce->createView()
        ));

    }
    /**
     * Displays a form to edit an existing categorie entity.
     *
     * @Route("/annonce/{id}/edit/categorie", name="da_annonce_cat_edit")
     * @Method({"GET", "POST"})
     */
    public function editCategorieAction(Request $request, CategorieAnnonce $categorieAnnonce)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $editFormcat = $this->createForm('EcoBundle\Form\CategorieAnnonceType', $categorieAnnonce);
        $editFormcat->handleRequest($request);
        if ($editFormcat->isSubmitted() && $editFormcat->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('da_annonce_cat_edit', array('id' => $categorieAnnonce->getId()));
        }
        return $this->render('@Eco/DashboardAdmin/Annonce/editCat.html.twig', array(
            'Categories' => $categorieAnnonce,
            'formcat'    => $editFormcat->createView(),
        ));

    }
    /**
     * Displays a form to edit an existing Annonce entity.
     *
     * @Route("/annonce/{id}/editAnnonce", name="da_annonce_edit")
     * @Method({"GET", "POST"})
     */
    public function editAnnonceAction(Request $request, Annonce $annonce)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }


        $editFormAnn = $this->createForm('EcoBundle\Form\AnnonceType',$annonce);
        $editFormAnn->handleRequest($request);
        if ($editFormAnn->isSubmitted() && $editFormAnn->isValid())
        {
            $annonce->setDateUpdate(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('da_annonce_edit', array('id' => $annonce->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Annonce/editAnnonce.html.twig', array(
            'annonce'    => $annonce,
            'formAnn'    => $editFormAnn->createView(),
        ));

    }
    /**
     * Deletes a annonce entity.
     *
     * @Route("/annonce/delete/{id}", name="da_annonce_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAnnonceAction($id)
    {
        $annonce = $this->getDoctrine()->getRepository('EcoBundle:Annonce')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();

        return $this->redirectToRoute('da_annonce_index');
    }
    /**
     * Deletes a categorie entity.
     *
     * @Route("/annonce/categorie/delete/{id}", name="da_annonce_cat_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteCategorieAction($id)
    {

        $categorieAnnonce = $this->getDoctrine()->getRepository('EcoBundle:CategorieAnnonce')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorieAnnonce);
        $em->flush();

        return $this->redirectToRoute('da_annonce_index');
    }
}
