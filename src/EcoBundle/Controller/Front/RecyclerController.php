<?php
/**
 * Created by PhpStorm.
 * User: Rania
 * Date: 19/02/2019
 * Time: 15:48
 */

namespace EcoBundle\Controller\Front;
use EcoBundle\Entity\CategorieMission;
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
use EcoBundle\Entity\Missions;
use EcoBunde\Form\rechercheEventType;

/**
 *
 * @Route("/front")
 */

class RecyclerController extends Controller
{
    /**
     * @Route("/evenement", name="front_evenements_index")
     * @Method("GET")
     */

    public function indexEventAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $search = [];
        $evenements = array();
        $search['categorie'] = $request->get('categorie', null);
        $search['lieu'] = $request->get('lieu', null);
        $search['date'] = $request->get('date', null);
//        var_dump($search);die;


        if ($search['categorie']) {
            $evenementsCat = $em->getRepository('EcoBundle:Missions')->searchByCategorieEvt($search['categorie']);
            if ($search['lieu']) {
                foreach ($evenementsCat as $event) {
                    if  ($event->getLieu() == $search['lieu']) {
                        $evenements[] = $event;
                    }
                }
            }else {
                $evenements = $evenementsCat;
            }
        }

//       $evenements =  $em->getRepository('EcoBundle:Missions')->search($search);

//      var_dump($evenements);die;
        $evenements = $em->getRepository('EcoBundle:Missions')->findAll();
        $categories = $em->getRepository('EcoBundle:CategorieMission')->findAll();
        return $this->render('@Eco/Front/Missions/index.html.twig', array(
            //'evenements' => $evenements,
            'evenements' => $evenements,
            'categories'=> $categories,
        ));
    }

    /**
     * @Route("/evenement/categorie/{cat}", name="front_evenements_recherche")
     * @Method("GET")
     */
    public function RecherchTestAction(Request $request,$cat)
    {

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('EcoBundle:CategorieMission')->findAll();
        $evenement = new Missions();
        $evenement = $em->getRepository('EcoBundle:Missions')->findByCategorie($cat);

        return $this->render('@Eco/Front/Missions/index.html.twig', array(
            "evenements"=>$evenement,'categories'=> $categories,

        ));
    }
    /**
     * @Route("/evenement/{id}", name="front_evenements_show")
     * @Method("GET")
     */
    public function showAction(Missions $evenement)
    {

        $evenement->setNbVues($evenement->getNbVues()+1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $deleteForm = $this->createDeleteForm($evenement);

        return $this->render('@Eco/Front/Missions/show.html.twig', array(
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
    public function deleteAction(Request $request, Missions $evenement)
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
        $evenement = new Missions();
        $form=$this->createForm(FilterType::class ,$evenement);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $evenements = $em->getRepository(Missions::class)->filterEvts($evenement->getLieu());
        } else {
            $evenements = $em->getRepository(Missions::class)->findAll();
        }

        return $this->render('@Eco/Front/Missions/filterEvts.html.twig',array(
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
    private function createDeleteForm(Missions $evenement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('front_evenements_delete', array('id' => $evenement->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }


    /**
     * @Route("/evenement/recherche", name="front_evenements_recherche")
     * @Method("GET")
     */

   /* public function rechercherLieuAction(Request $request)
    {
        $event= new Missions();
        $form= $this->createForm(rechercheEventType::class ,$event);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $events= $this->getDoctrine()->getRepository(Missions::class)
                ->findBy(array('lieu'=>$event->getLieu()));
        }
        else{
            $events= $this->getDoctrine()->getRepository(Missions::class)
                ->findAll();
        }
        return $this->render('@Eco/Front/Missions/rechercheEvent.html.twig',array("form"=>$form->createView(),'events'=>$events));

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
                    ->from('EcoBundle:Missions', 'a')
                    ->where("a.lieu LIKE :motcle ")
                    ->orderBy('a.lieu', 'ASC')
                    ->setParameter('motcle', '%'.$motcle.'%');

                $query = $qb->getQuery();
                $events = $query->getResult();
            }
            else {
                $events = $em->getRepository('EcoBundle:Missions')->findAll();
            }

            return $this->container->get('templating')->renderResponse('EcoBundle:Missions:index.html.twig', array(
                'events' => $events
            ));
        }
        else {
            return $this->listerAction();
        }
    }*/

    /**
     * @Route("/evenementP/{id}", name="front_evenements_participer")
     * @Method("GET")
     */
   public function participerAction($id)
   {
       $em = $this->getDoctrine()->getManager();
       $evenement =  $em->getRepository('EcoBundle:Missions')->find($id);
       $user = $this->get('security.token_storage')->getToken()->getUser();

       $evenement->addProfile($user);
       $user->addEventsParticipes($evenement);

       $em->persist($evenement);
       $em->persist($user);
       $em->flush();
       return $this->redirectToRoute('front_evenements_index');
       $this->addFlash("success", "Vous avez participer avec succés  ! ");

   }

    /**
     * @Route("/evenementNP/{id}", name="front_evenements_noparticiper")
     * @Method("GET")
     */
    public function noParticiperAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $evenement =  $em->getRepository('EcoBundle:Missions')->find($id);
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $evenement->removeProfile($user);
        $user->removeEventsParticipes($evenement);

       // $em->persist($evenement);
     //   $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('front_evenements_index');
        $this->addFlash("warning", "Vous avez annuler votre participation ! ");

    }



}