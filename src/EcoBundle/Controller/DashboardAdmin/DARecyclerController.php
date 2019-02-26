<?php
/**
 * Created by PhpStorm.
 * User: Mehdi
 * Date: 14/02/2019
 * Time: 22:04
 */

namespace EcoBundle\Controller\DashboardAdmin;

use EcoBundle\Entity\CategorieMission;
use EcoBundle\Entity\Missions;
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
class DARecyclerController extends Controller
{
    /**
     * @Route("/categorie", name="da_categorie_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();

        //$evenements = $em->getRepository('EcoBundle:Evenements')->findAll();
        $categoriesEvts = $em->getRepository('EcoBundle:CategorieMission')->findAll();
//        w tkamel tu récupére les autres entités li aand'hom 3ala9a bel module mta3ek

        return $this->render('@Eco/DashboardAdmin/Missions/index.html.twig', array(
            //'evenements' => $evenements,
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
       // $evenement = new Missions();
        $categorieEvts = new CategorieMission();
//        tasna3 les autres entités li aand'hom 3ala9a bel module mta3ek

       // $formEvt = $this->createForm('EcoBundle\Form\MissionsType', $evenement);
        $formCateg = $this->createForm('EcoBundle\Form\CategorieMissionType', $categorieEvts);
//        tasna3 les autres formulaire li aand'hom 3ala9a bel module mta3ek

        //$formEvt->handleRequest($request);
        $formCateg->handleRequest($request);
//       tu handleRequesti :p les autres formulaire li aand'hom 3ala9a bel module mta3ek



       /* if ($formEvt->isSubmitted() && $formEvt->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute('da_evenements_index');
        }*/
        if ($formCateg->isSubmitted() && $formCateg->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorieEvts);
            $em->flush();
            return $this->redirectToRoute('da_categorie_index');
        }
//        lahna tu persiste l'entité selon le formulaire adéquat mta3ha ( Exple pour $formEvt tu persiste $evenement )
//        w tab9a t3awed fihom 3la 3dad les entités que tu gére

        return $this->render('@Eco/DashboardAdmin/Missions/new.html.twig', array(
            'CategorieMission' => $categorieEvts,
           // 'formEvt' => $formEvt->createView(),
            'formCateg' => $formCateg->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/categorie/{id}/edit", name="da_categorie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CategorieMission $categorieEvts)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $editForm = $this->createForm('EcoBundle\Form\CategorieMissionType', $categorieEvts);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('da_categorie_index', array('id' => $categorieEvts->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Missions/edit.html.twig', array(
            'CategorieMission' => $categorieEvts,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/categorie/{id}", name="da_categorie_show")
     * @Method("GET")
     */
    public function showAction(CategorieMission $categorieEvts)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        return $this->render('@Eco/DashboardAdmin/Missions/show.html.twig', array(
            'CategorieMission' => $categorieEvts,
        ));
    }



    /**
     * @Route("/evenement", name="da_evenements_index")
     * @Method("GET")
     */

    public function indexEventAction()
    {
          if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
              throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
          }
        $em = $this->getDoctrine()->getManager();
        //$evenements = $em->getRepository('EcoBundle:Evenements')->findAll();

//        w tkamel tu récupére les autres entités li aand'hom 3ala9a bel module mta3ek
        $evenement = $em->getRepository('EcoBundle:Missions')->findAll();
        return $this->render('@Eco/DashboardAdmin/Missions/indexEvent.html.twig', array(
            //'evenements' => $evenements,
            'evenement' => $evenement,
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
        $evenement = $m->getRepository(Missions::class)->find($id);
        $m->remove($evenement);
        $m->flush();

        return$this->redirectToRoute('da_evenements_index');
    }


}