<?php

namespace EcoBundle\Controller\DashboardUser;

use EcoBundle\Entity\Annonce;
use EcoBundle\Form\RechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;


/**
 *
 * @Route("du")
 */
class DUAnnonceController extends Controller
{

    /**
 * @Route("/annonce", name="du_annonce_index")
 * @Method("GET")
 */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        //$annonces = $user->getMyAnnonces();
//        $em = $this->getDoctrine()->getManager();
        $annonces = $em->getRepository('EcoBundle:Annonce')->findByUser($user);

        //var_dump($annonces);die;

        return $this->render('@Eco/DashboardUser/Annonce/index.html.twig', array(
            'useer' => $user,
            'annonces' => $annonces,
        ));
    }
    /**
     * Creates a new Categorie et annonce entity.
     *
     * @Route("/annonce/new", name="du_annonce_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $annonce = new Annonce();
        $formAnnonce = $this->createForm('EcoBundle\Form\AnnonceType',$annonce);
        $formAnnonce->handleRequest($request);
        if($formAnnonce->isSubmitted() && $formAnnonce->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $annonce->setEtat("Disponible");
            $annonce->setUser($user);
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('du_annonce_index');

        }
        return $this->render('@Eco/DashboardUser/Annonce/new.html.twig', array(
            'formAnnonce' => $formAnnonce->createView()
        ));

    }
    /**
     * Finds and displays a user entity.
     *
     * @Route("/annonce/{id}", name="du_annonce_show")
     * @Method("GET")
     */
    public function showAction(Annonce $annonce)
    {

        return $this->render('@Eco/DashboardUser/Annonce/show.html.twig', array(
            'annonce' => $annonce,
        ));
    }
    /**
     * Displays a form to edit an existing Annonce entity.
     *
     * @Route("/annonce/{id}/editAnnonce", name="du_annonce_edit")
     * @Method({"GET", "POST"})
     */
    public function editAnnonceAction(Request $request, Annonce $annonce)
    {
        $editFormAnn = $this->createForm('EcoBundle\Form\AnnonceType',$annonce);
        $editFormAnn->handleRequest($request);
        if ($editFormAnn->isSubmitted() && $editFormAnn->isValid())
        {
            $annonce->setDateUpdate(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('du_annonce_edit', array('id' => $annonce->getId()));
        }

        return $this->render('@Eco/DashboardUser/Annonce/editAnnonce.html.twig', array(
            'annonce'    => $annonce,
            'formAnn'    => $editFormAnn->createView(),
        ));

    }
    /**
     * Deletes a annonce entity.
     *
     * @Route("/annonce/delete/{id}", name="du_annonce_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAnnonceAction($id)
    {
        $annonce = $this->getDoctrine()->getRepository('EcoBundle:Annonce')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();

        return $this->redirectToRoute('du_annonce_index');
    }
}
