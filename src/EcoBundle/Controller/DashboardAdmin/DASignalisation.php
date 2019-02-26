<?php

namespace EcoBundle\Controller\DashboardAdmin;

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
class DASignalisation extends Controller
{
    /**
    * @Route("/signalisation", name="da_signalisation_index")
    * @Method("GET")
    */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();

        $signalisationForumComms = $em->getRepository('EcoBundle:SignalisationForumComm')->findAll();
        $signalAnnonces = $em->getRepository('EcoBundle:SignalAnnonce')->findAll();

        return $this->render('@Eco/DashboardAdmin/Signalisation/index.html.twig', array(
            'signalisationForumComms' => $signalisationForumComms,
            'signalAnnonces' => $signalAnnonces,
        ));
    }

    /**
     * @Route("/signalisation/commentaire/delete/{id}", name="da_signalisation_comm_forum_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteSignalisationForumCommAction(Request $request, $id)
    {
        $signalisation = $this->getDoctrine()->getRepository('EcoBundle:SignalisationForumComm')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($signalisation);
        $em->flush();
        return $this->redirectToRoute('da_signalisation_index');
    }

    /**
     * @Route("/signalisation/annonce/delete/{id}", name="da_signalisation_annonce_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteSignalAnnoncesAction(Request $request, $id)
    {
        $signalisation = $this->getDoctrine()->getRepository('EcoBundle:SignalAnnonce')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($signalisation);
        $em->flush();
        return $this->redirectToRoute('da_signalisation_index');
    }


}