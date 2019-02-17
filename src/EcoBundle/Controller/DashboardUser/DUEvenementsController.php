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

       // $categoriesEvts = $em->getRepository('EcoBundle:CategorieEvts')->findAll();
//        w tkamel tu récupére les autres entités li aand'hom 3ala9a bel module mta3ek

        return $this->render('@Eco/DashboardUser/Evenement/index.html.twig', array(
            'evenements' => $evenements,
            //'categoriesEvts' => $categoriesEvts,
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/evenement/new", name="du_evenements_new")
     * @Method({"GET", "POST"})
     */
  /*  public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $evenement = new Evenement();
//        tasna3 les autres entités li aand'hom 3ala9a bel module mta3ek

        $formEvt = $this->createForm('EcoBundle\Form\EvenementType', $evenement);
//        tasna3 les autres formulaire li aand'hom 3ala9a bel module mta3ek

        //tforci le createur de l'evenement
        $evenement->setCreateur($user);


        //$formEvt->handleRequest($request);
        $formCateg->handleRequest($request);
//       tu handleRequesti :p les autres formulaire li aand'hom 3ala9a bel module mta3ek



        if ($formEvt->isSubmitted() && $formEvt->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute('da_evenements_index');
        }
//        lahna tu persiste l'entité selon le formulaire adéquat mta3ha ( Exple pour $formEvt tu persiste $evenement )
//        w tab9a t3awed fihom 3la 3dad les entités que tu gére

        return $this->render('@Eco/DashboardAdmin/Evenement/new.html.twig', array(
            'formEvt' => $formEvt->createView(),
        ));
    }*/

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/evenement/{id}/edit", name="du_evenements_edit")
     * @Method({"GET", "POST"})
     */
  /*  public function editAction(Request $request, CategorieEvts $categorieEvts)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $deleteForm = $this->createDeleteForm($categorieEvts);
        $editForm = $this->createForm('EcoBundle\Form\CategorieEvtsType', $categorieEvts);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('da_evenements_edit', array('id' => $categorieEvts->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Evenement/edit.html.twig', array(
            'CategorieEvts' => $categorieEvts,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }*/

    /**
     * Finds and displays a user entity.
     *
     * @Route("/evenement/{id}", name="du_evenements_show")
     * @Method("GET")
     */
   public function showAction(Evenement $evenement)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($evenement->getCreatedBy()== $user)
        {
        $deleteForm = $this->createDeleteForm($evenement);

        return $this->render('@Eco/DashboardUser/Evenement/show.html.twig', array(
            'Evenement' => $evenement,
            'delete_form' => $deleteForm->createView(),
        )); }
    }



    /**
     * Deletes a Reparateur entity.
     *
     * @Route("/evenement/{id}", name="du_evenements_delete")
     * @Method("DELETE")
     */
   public function deleteAction(Request $request, Evenement $evenement)
   {
       $form = $this->createDeleteForm($evenement);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $em = $this->getDoctrine()->getManager();
           $em->remove($evenement);
           $em->flush();
       }

       return $this->redirectToRoute('du_evenements_index');
   }

    /**
     * Creates a form to delete a Reparateur entity.
     *
     * @param Reparateur $categorieEvts The Reparateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
  private function createDeleteForm(Evenement $evenement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('du_evenements_delete', array('id' => $evenement->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}