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
        $categoriesEvts = $em->getRepository('EcoBundle:CategorieEvts')->findAll();
//        w tkamel tu récupére les autres entités li aand'hom 3ala9a bel module mta3ek

        return $this->render('@Eco/DashboardAdmin/Evenement/index.html.twig', array(
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
       // $evenement = new Evenement();
        $categorieEvts = new CategorieEvts();
//        tasna3 les autres entités li aand'hom 3ala9a bel module mta3ek

       // $formEvt = $this->createForm('EcoBundle\Form\EvenementType', $evenement);
        $formCateg = $this->createForm('EcoBundle\Form\CategorieEvtsType', $categorieEvts);
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

        return $this->render('@Eco/DashboardAdmin/Evenement/new.html.twig', array(
            'CategorieEvts' => $categorieEvts,
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
    public function editAction(Request $request, CategorieEvts $categorieEvts)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $deleteForm = $this->createDeleteForm($categorieEvts);
        $editForm = $this->createForm('EcoBundle\Form\CategorieEvtsType', $categorieEvts);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('da_categorie_index', array('id' => $categorieEvts->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Evenement/edit.html.twig', array(
            'CategorieEvts' => $categorieEvts,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
        $deleteForm = $this->createDeleteForm($categorieEvts);

        return $this->render('@Eco/DashboardAdmin/Evenement/show.html.twig', array(
            'CategorieEvts' => $categorieEvts,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a Reparateur entity.
     *
     * @Route("/categorie/{id}", name="da_categorie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CategorieEvts $categorieEvts)
    {
        $form = $this->createDeleteForm($categorieEvts);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorieEvts);
            $em->flush();
        }

        return $this->redirectToRoute('da_categorie_index');
    }

    /**
     * Creates a form to delete a Reparateur entity.
     *
     * @param Reparateur $categorieEvts The Reparateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CategorieEvts $categorieEvts)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('da_categorie_delete', array('id' => $categorieEvts->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
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
        $evenement = $em->getRepository('EcoBundle:Evenement')->findAll();
        return $this->render('@Eco/DashboardAdmin/Evenement/indexEvent.html.twig', array(
            //'evenements' => $evenements,
            'evenement' => $evenement,
        ));
    }

    /**
     * Deletes a Reparateur entity.
     *
     * @Route("/evenement/{id}", name="da_evenement_delete")
     * @Method("DELETE")
     */

    public function deleteEventAction($id)
    {
        $m=$this->getDoctrine()->getManager();
        $event=$m->getRepository(Evenement::class)->find($id);
        $m->remove($event);
        $m->flush();

        return$this->redirectToRoute('da_evenements_index');
    }




}