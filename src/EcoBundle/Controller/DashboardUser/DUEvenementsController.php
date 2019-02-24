<?php
/**
 * Created by PhpStorm.
 * User: Rania
 * Date: 14/02/2019
 * Time: 22:04
 */

namespace EcoBundle\Controller\DashboardUser;

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
 * @Route("du")
 */

class DUEvenementsController extends Controller
{
    /**
     * @Route("/evenement", name="du_evenements_index")
     * @Method("GET")
     */
    public function indexAction()
    {
      /*  $evenements = array();
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }*/
//        eli 7ajetna bihom mellaa5er
        $events = array();

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
//        var_dump($idUser = $user->getId());die;
//        var_dump($idGroup = $user->getGroup());die;

//        na5dhou tout les evenements
        $evenements = $em->getRepository('EcoBundle:Evenement')->findBy(array('createdBy'=>$user));

        foreach ($evenements as $e) {
//            anou li connecté ykoun houwa createur de l evenement
            if ($e->getCreatedBy()== $user) {
                $events[] = $e;
            }
//            anou li connecté ykoun houwa de meme groupe que le createur de l evenement
          /*  if ($user->getGroups()) { //test idha 3andou deja groupe ( ma3neha en faite houwa respAsso walla respSoc )
                if ($e->getCreatedBy()->getGroup() == $user->getGroup()) {
                    $events[] = $e;
//                  bech ylemhom
                }
            }*/
        }
//       $categoriesEvts = $em->getRepository('EcoBundle:CategorieEvts')->findAll();
//        w tkamel tu récupére les autres entités li aand'hom 3ala9a bel module mta3ek

        return $this->render('@Eco/DashboardUser/Evenement/index.html.twig', array(
            'evenements' => $evenements,
            //'categoriesEvts' => $categoriesEvts,
        ));
    }

    /**
     *
     * @Route("/evenement/new", name="du_evenements_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $evenement = new Evenement();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm('EcoBundle\Form\EvenementType', $evenement);
        $form->handleRequest($request);
        $evenement->setCreatedBy($user);
        $evenement->setNbVues(0);
        $user->addEventsCrees($evenement);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute('du_evenements_index');
        }
        return $this->render('@Eco/DashboardUser/Evenement/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/evenement/{id}/edit", name="du_evenement_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request,Evenement $evenement)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($evenement->getCreatedBy()!= $user)
        {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        $editForm = $this->createForm('EcoBundle\Form\EvenementType', $evenement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('du_evenements_index', array('id' => $evenement->getId()));
        }

        return $this->render('@Eco/DashboardUser/Evenement/edit.html.twig', array(
            'evenement' => $evenement,
            'form' => $editForm->createView(),
        ));
    }


    /**
     * @Route("/evenement/{id}", name="du_evenements_show")
     * @Method("GET")
     */
    public function showAction(Evenement $evenement)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($evenement->getCreatedBy()!= $user)
        {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        return $this->render('@Eco/DashboardUser/Evenement/show.html.twig', array(
            'evenement' => $evenement,
        ));
    }

    /**
     * Deletes a Reparateur entity.
     *
     * @Route("/evenement/delete/{id}", name="du_evenements_delete")
     */
    public function deleteEventAction($id)
    {
        $m=$this->getDoctrine()->getManager();
        $evenement = $m->getRepository(Evenement::class)->find($id);
        $m->remove($evenement);
        $m->flush();

        return$this->redirectToRoute('du_evenements_index');
    }


}