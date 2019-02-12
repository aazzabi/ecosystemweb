<?php
/**
 * Created by PhpStorm.
 * User: arafe
 * Date: 12/02/2019
 * Time: 20:44
 */

namespace EcoBundle\Controller;


use EcoBundle\Entity\Reparateur;
use EcoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("inscription")
 */
class InscriptionController extends Controller
{
    /**
     *
     * @Route("/", name="inscription")
     */
    public function newAction(Request $request)
    {
        $reparateur = new Reparateur();
        $user = new User();

        $formRep = $this->createForm(  'EcoBundle\Form\ReparateurType', $reparateur);
        $formUser = $this->createForm(  'EcoBundle\Form\UserType', $user);

        $formRep->handleRequest($request);
        $formUser->handleRequest($request);


        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('reparateur_show', array('id' => $reparateur->getId()));
        }

        if ($formRep->isSubmitted() && $formRep->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reparateur);
            $em->flush();

            return $this->redirectToRoute('reparateur_show', array('id' => $reparateur->getId()));
        }


        return $this->render('@Eco/Default/inscription.html.twig', array(
            'formUser' => $formUser->createView(),
            'formRep' => $formRep->createView(),
        ));
    }

}