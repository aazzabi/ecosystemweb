<?php
/**
 * Created by PhpStorm.
 * User: Rania
 * Date: 19/02/2019
 * Time: 15:48
 */

namespace EcoBundle\Controller\Front;
use EcoBundle\Entity\CategorieEvts;
use EcoBundle\Entity\Group;
use EcoBundle\Entity\Livreur;
use EcoBundle\Entity\Reparateur;
use EcoBundle\Entity\RespAsso;
use EcoBundle\Entity\RespSoc;
use EcoBundle\Entity\User;
use EcoBundle\Form\FilterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EcoBundle\Entity\Evenement;
use EcoBunde\Form\rechercheEventType;

/**
 *
 * @Route("/front")
 */

class EvenementController extends Controller
{
    /**
     * @Route("/evenement", name="front_evenements_index")
     * @Method("GET")
     */

    public function indexEventAction()
    {

        $em = $this->getDoctrine()->getManager();

        //$evenements = $em->getRepository('EcoBundle:Evenements')->findAll();

//        w tkamel tu récupére les autres entités li aand'hom 3ala9a bel module mta3ek
        $evenements = $em->getRepository('EcoBundle:Evenement')->findAll();
        $categories = $em->getRepository('EcoBundle:CategorieEvts')->findAll();
        //$form = $this->container->get('form.factory')->create(new rechercheEventType());
        return $this->render('@Eco/Front/Evenement/index.html.twig', array(
            //'evenements' => $evenements,
            'evenements' => $evenements,'categories'=> $categories,
        ));
    }

    /**
     * @Route("/evenement/{id}", name="front_evenements_show")
     * @Method("GET")
     */
    public function showAction(Evenement $evenement)
    {

        $evenement->setNbVues($evenement->getNbVues()+1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $deleteForm = $this->createDeleteForm($evenement);

        return $this->render('@Eco/Front/Evenement/show.html.twig', array(
            'evenement' => $evenement,
            'delete_form' => $deleteForm->createView(),
        ));


    }
    /**
     * Deletes a Reparateur entity.
     *
     * @Route("/evenement/{id}", name="front_evenements_delete")
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
        return $this->redirectToRoute('front_evenements_index');
    }

    /**
     * @Route("/evenementfilter", name="front_evenements_filter")
     * @Method("POST")
     */

    public function filterEventsAction(Request $request)
    {
        var_dump("hey");

        $em = $this->getDoctrine()->getManager();
        $evenement = new Evenement();
        $form=$this->createForm(FilterType::class ,$evenement);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $evenements = $em->getRepository(Evenement::class)->filterEvts($evenement->getLieu());
        } else {
            $evenements = $em->getRepository(Evenement::class)->findAll();
        }

        return $this->render('@Eco/Front/Evenement/filterEvts.html.twig',array(
            'formSearch'=>$form->createView(),
            "evenements"=>$evenements
        ));
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
            ->setAction($this->generateUrl('front_evenements_delete', array('id' => $evenement->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @Route("/evenement/categorie/{cat}", name="front_evenements_recherche")
     * @Method("GET")
     */
    public function RecherchTestAction(Request $request,$cat)
    {

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('EcoBundle:CategorieEvts')->findAll();
        $evenement = new Evenement();
        $evenement = $em->getRepository('EcoBundle:Evenement')->findByCategorie($cat);

        return $this->render('@Eco/Front/Evenement/index.html.twig', array(
            "evenements"=>$evenement,'categories'=> $categories,

        ));
    }

    /**
     * @Route("/evenement/recherche", name="front_evenements_recherche")
     * @Method("GET")
     */

   /* public function rechercherLieuAction(Request $request)
    {
        $event= new Evenement();
        $form= $this->createForm(rechercheEventType::class ,$event);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $events= $this->getDoctrine()->getRepository(Evenement::class)
                ->findBy(array('lieu'=>$event->getLieu()));
        }
        else{
            $events= $this->getDoctrine()->getRepository(Evenement::class)
                ->findAll();
        }
        return $this->render('@Eco/Front/Evenement/rechercheEvent.html.twig',array("form"=>$form->createView(),'events'=>$events));

    }*/

    /**
     * @Route("/evenement/rechercheajax", name="front_evenements_rechercheajax")
     * @Method("POST")
     */
  /*  public function rechercherAjaxAction()
    {
        $request = $this->container->get('request');

        if($request->isXmlHttpRequest())
        {
            $motcle = '';
            $motcle = $request->request->get('motcle');

            $em = $this->container->get('doctrine')->getEntityManager();

            if($motcle != '')
            {
                $qb = $em->createQueryBuilder();

                $qb->select('a')
                    ->from('EcoBundle:Evenement', 'a')
                    ->where("a.lieu LIKE :motcle ")
                    ->orderBy('a.lieu', 'ASC')
                    ->setParameter('motcle', '%'.$motcle.'%');

                $query = $qb->getQuery();
                $events = $query->getResult();
            }
            else {
                $events = $em->getRepository('EcoBundle:Evenement')->findAll();
            }

            return $this->container->get('templating')->renderResponse('EcoBundle:Evenement:index.html.twig', array(
                'events' => $events
            ));
        }
        else {
            return $this->listerAction();
        }
    }*/






}