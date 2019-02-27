<?php
/**
 * Created by PhpStorm.
 * User: Rania
 * Date: 14/02/2019
 * Time: 22:04
 */

namespace EcoBundle\Controller\DashboardAdmin;

use EcoBundle\Entity\CategorieEvts;
use EcoBundle\Entity\Evenement;
use EcoBundle\Entity\Group;
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
class DAEvenementsController extends Controller
{
    /**
     * @Route("/evenement", name="da_evenements_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository('EcoBundle:Evenement')->findAll();
        $categoriesEvts = $em->getRepository('EcoBundle:CategorieEvts')->findAll();

        return $this->render('@Eco/DashboardAdmin/Evenement/index.html.twig', array(
            'evenements' => $evenements,
            'categoriesEvts' => $categoriesEvts,
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/categorie/new", name="da_categorie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $categorieEvts = new CategorieEvts();
        $formCateg = $this->createForm('EcoBundle\Form\CategorieEvtsType', $categorieEvts);
        $formCateg->handleRequest($request);

        if ($formCateg->isSubmitted() && $formCateg->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorieEvts);
            $em->flush();
            return $this->redirectToRoute('da_evenements_index');
        }

        return $this->render('@Eco/DashboardAdmin/Evenement/new.html.twig', array(
            'CategorieEvts' => $categorieEvts,
            'formCateg' => $formCateg->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/categorie/{id}/edit", name="da_categorie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CategorieEvts $categorieEvts)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $editForm = $this->createForm('EcoBundle\Form\CategorieEvtsType', $categorieEvts);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('da_evenements_index', array('id' => $categorieEvts->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Evenement/edit.html.twig', array(
            'CategorieEvts' => $categorieEvts,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/categorie/{id}", name="da_categorie_show")
     * @Method("GET")
     */
    public function showAction(CategorieEvts $categorieEvts)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        return $this->render('@Eco/DashboardAdmin/Evenement/show.html.twig', array(
            'CategorieEvts' => $categorieEvts,
        ));
    }

    /**
     * Deletes a Reparateur entity.
     *
     * @Route("/evenement/delete/{id}", name="da_evenements_delete")
     */
    public function deleteEventAction($id)
    {
        $m=$this->getDoctrine()->getManager();
        $evenement = $m->getRepository(Evenement::class)->find($id);
        $m->remove($evenement);
        $m->flush();

        return$this->redirectToRoute('da_evenements_index');
    }


    /**
     * Deletes a Reparateur entity.
     *
     * @Route("/categorie/delete/{id}", name="da_categorie_delete")
     */
    public function deleteCategorietAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $categorieEvt = $em->getRepository(CategorieEvts::class)->find($id);
        $em->remove($categorieEvt);
        $em->flush();

        return$this->redirectToRoute('da_evenements_index');
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/evenement/{id}", name="da_evenement_show")
     * @Method("GET")
     */
    public function showEventAction(Evenement $evenement)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        return $this->render('@Eco/DashboardAdmin/Evenement/showEvent.html.twig', array(
            'evenement' => $evenement,
        ));
    }


}