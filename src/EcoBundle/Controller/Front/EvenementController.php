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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EcoBundle\Entity\Evenement;
/**
 *
 * @Route("front")
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
        return $this->render('@Eco/Front/Evenement/index.html.twig', array(
            //'evenements' => $evenements,
            'evenements' => $evenements,
        ));
    }

    /**
     * @Route("/evenement/{id}", name="front_evenements_show")
     * @Method("GET")
     */
    public function showAction(Evenement $evenement)
    {
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
}