<?php
/**
 * Created by PhpStorm.
 * User: Rania
 * Date: 19/02/2019
 * Time: 15:48
 */

namespace EcoBundle\Controller\Front;
use EcoBundle\Controller\API\EvenementAPIController;
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

use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function indexEventAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $search = [];
        $evenements = array();
        $search['categorie'] = $request->get('categorie', null);
        $search['lieu'] = $request->get('lieu', null);
        $date = $request->get('date', null);
        $search['date'] = date('Y-M-D', strtotime($date));
//        var_dump($search);die;

//        var_dump($search);die;
//        $evenements = $em->getRepository('EcoBundle:Evenement')->findAll();

//        if ($search['categorie']) {
//            $evenementsCat = $em->getRepository('EcoBundle:Evenement')->searchByCategorieEvt($search['categorie']);
//            if ($search['lieu']) {
//                foreach ($evenementsCat as $event) {
//                    if  ($event->getLieu() == $search['lieu']) {
//                        $evenements[] = $event;
//                    }
//                }
//            }else {
//                $evenements = $evenementsCat;
//            }
//        }
        if (!$search['lieu'] && !$search['date'] && !$search['categorie'] ) {
            $evenements = $em->getRepository('EcoBundle:Evenement')->findAll();
        } else {
            $evenements = $em->getRepository('EcoBundle:Evenement')->search($search);
        }

//         var_dump($evenements);die;

        $categories = $em->getRepository('EcoBundle:CategorieEvts')->findAll();
        return $this->render('@Eco/Front/Evenement/index.html.twig', array(
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
        $categories = $em->getRepository('EcoBundle:CategorieEvts')->findAll();
        $evenement = new Evenement();
        $evenement = $em->getRepository('EcoBundle:Evenement')->findByCategorie($cat);

        return $this->render('@Eco/Front/Evenement/index.html.twig', array(
            "evenements"=>$evenement,'categories'=> $categories,

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

    /**
     * @Route("/evenementP", name="front_evenements_participer")
     * @Method({"GET", "POST"})
     */
   public function participerAction(Request $request)
   {
       $id = $request->get('id');
       $em = $this->getDoctrine()->getManager();

       if ($request->isXmlHttpRequest())
       {
           $evenement =  $em->getRepository('EcoBundle:Evenement')->find($id);
           $user = $this->get('security.token_storage')->getToken()->getUser();
           $evenement->addParticipant($user);
//           $user->addEventsParticipes($evenement);
           $em->persist($evenement);
           $em->persist($user);
           $em->flush();

           $arrData = ['msg' => "ok super"];
           return new JsonResponse($arrData);
       }
       return $this->redirectToRoute('front_evenements_index');
   }
    /**
     * @Route("/evenementNP", name="front_evenements_noparticiper")
     * @Method("GET")
     */
    public function noParticiperAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest())
        {
            $evenement =  $em->getRepository('EcoBundle:Evenement')->find($id);
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $evenement->removeParticipants($user);
            $user->removeEventsParticipes($evenement);
            $em->persist($evenement);
            $em->persist($user);
            $em->flush();

            $arrData = ['msg' => "ok super"];
            return new JsonResponse($arrData);
        }
        return $this->redirectToRoute('front_evenements_index');

    }

    /**
     * @Route("/evenementM", name="front_mail_event")
     * @Method("GET")
     */
    public function SendMailReclamAction(Request $request )
    {
      // $reclam=$this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        if($request->getMethod()=="GET")
        {
            $Subject=$request->get("Subject");
            $email=$request->get("Email");
            $message=$request->get("message");
            $mailer=$this->container->get('mailer');
            $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com',465,'ssl')
                ->setUsername('arafet.azzabi@esprit.tn')
                ->setPassword('183JMT0449');
            $mailer=\Swift_Mailer::newInstance($transport);
            $message=\Swift_Message::newInstance('test')
                ->setSubject($Subject)
                ->setFrom('arafet.azzabi@esprit.tn')
                ->setTo($email)
                ->setBody($message);
            $this->get('mailer')->send($message);
           // $reclam->setEtat('1');

            return  $this->redirectToRoute("front_evenements_index");

        }
        return $this->render("@Eco/MailParticiper.html.twig");
    }

    /**
     * @Route("/evenement/oui/ajax", name="front_event_participer_ajax")
     * @Method({"GET", "POST"})
     */
    public function participerAjaxAction(Request $request)
    {
        $id        = $request->get('id');
        $em        = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository('EcoBundle:Evenement')->find($id);
        $user      = $this->get('security.token_storage')->getToken()->getUser();
//        $user->addEventsParticipes($evenement);
        $evenement->addPartcipants($user);
        $em->persist($evenement);
        $em->persist($user);
        $em->flush();

        $events = $em->getRepository('EcoBundle:Evenement')->findAll();
        $template = $this->render(
            '@Eco/Front/Evenement/allEvents.html.twig',
            [
                'evenements' => $events,
            ]
        )->getContent()
        ;

        $json     = json_encode($template);
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * @Route("/evenement/no/ajax/", name="front_event_no_participer_ajax")
     * @Method("GET")
     */
    public function noParticiperAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $evenement =  $em->getRepository('EcoBundle:Evenement')->find($id);
        $user = $this->get('security.token_storage')->getToken()->getUser();
//        $user->removeEventsParticipes($evenement);
        $evenement->removeParticipants($user);
        $em->persist($evenement);
        $em->persist($user);
        $em->flush();

        $events = $em->getRepository('EcoBundle:Evenement')->findAll();

        $template = $this->render(
            '@Eco/Front/Evenement/allEvents.html.twig',
            [
                'evenements' => $events,
            ]
        )->getContent();

        $json     = json_encode($template);
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/evenementBest", name="front_evenements_Best")
     * @Method("GET")
     */
    public function findBestEventsAction()
    {
        $em=$this->getDoctrine()->getManager();
        $events=$em->getRepository(Evenement::class)->BestEvents();
        //var_dump($events);die;
        return $this->render('@Eco/Front/Evenement/indexBest.html.twig',array("events"=>$events));

    }



    /**
     * @Route("/jmsevent", name="jms")
     * @Method({"GET", "POST"})
     */
    public function jmsEventAction()
    {
       $event = $this->getDoctrine()->getManager()
             ->getRepository('EcoBundle:Evenement')->findAll();
       $serializer = new Serializer([new DateTimeNormalizer('d-M-Y'),new ObjectNormalizer()]);
       $formatted = $serializer->normalize($event);
       return new JsonResponse($formatted);


//       $events = $this->getDoctrine()->getManager()
//            ->getRepository(Evenement::class)
//            ->findAll();
//
//
//
//        $serializer = $this->get('jms_serializer');
//
//        $response = new Response($serializer->serialize($events, 'json'));
//        $response->headers->set('Content-Type', 'application/json');
//
//        return $response;


    }

    /**
     * @Route("/jmsoneevent/{id}", name="jmsone")
     * @Method({"GET", "POST"})
     */
    public function jmsEventShowAction($id)
    {

       // $deleteForm = $this->createDeleteForm($evenement);
        $event = $this->getDoctrine()->getManager()
            ->getRepository(Evenement::class)
            ->find($id);

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($event, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;


    }

    /**
     * @Route("/jmsaddevent/{id}", name="jmsadd")
     * @Method({"GET", "POST"})
     */
    public function newApiAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $event = new Evenement();
        $event->setTitre($request->get('titre'));
        $event->setDescription($request->get('description'));
        $event->setLieu($request->get('lieu'));

        $categorie = $em->getRepository(CategorieEvts::class)->find($request->get('categorie'));
        $user = $em->getRepository(User::class)->find($request->get('createdBy'));

        $event->setCategorie($categorie);
        $event->setCreatedBy($user);

        $em->persist($event);
        $em->flush();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($event, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}