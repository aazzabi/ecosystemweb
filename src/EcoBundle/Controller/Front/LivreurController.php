<?php
/**
 * Created by PhpStorm.
 * User: Aziz
 * Date: 27/02/2019
 * Time: 00:05
 */

namespace EcoBundle\Controller\Front;
/**
 *
 * @Route("livreur")
 */

use EcoBundle\Entity\Livraison;
use Ob\HighchartsBundle\Highcharts\Highchart;
use EcoBundle\Entity\Commande;
use EcoBundle\Entity\LigneCommande;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use EcoBundle\Entity\AnnoncePanier;
use EcoBundle\Entity\Annonce;
use EcoBundle\Entity\User;
use EcoBundle\Entity\CategorieAnnonce;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;

class LivreurController extends Controller
{
    /**
     *
     * @Route("/da_livreur", name="dashboard_livreur")
     * @Method("GET")
     */
    public function showAction()
    {
        return $this->render('default/da_page_livreur.html.twig');
    }


    /**
     *
     * @Route("/da_livreur/{id_l}", name="show_livraison_livreur")
     * @Method("GET")
     */
    public function show2Action()
    {
        $em=$this->getDoctrine()->getManager();
        $livraison = $em->getRepository('EcoBundle:Livraison')->findAll();
        $users = $em->getRepository('EcoBundle:User')->findAll();
        $commandes = $em->getRepository('EcoBundle:Commande')->findAll();
        return $this->render('@Eco/DashboardUser/da_page_index_livreur.html.twig', array(
            'users'=> $users,'livraison'=>$livraison,'commandes'=>$commandes));
    }

    /**
     *
     * @Route("/da_livreur/{id_l}/{id_liv}", name="changer_etat")
     * @Method("GET")
     */
    public function changeretatAction($id_l,$id_liv)
    {
        $em=$this->getDoctrine()->getManager();
        $livraison = $em->getRepository('EcoBundle:Livraison')->findAll();
        $commandes = $em->getRepository('EcoBundle:Commande')->findAll();
        $livreur = $em->getRepository('EcoBundle:Livreur')->findAll();
        foreach ($livraison as $livraison)
        {
            if($livraison->getId()==$id_liv)
            {
                $livraison->setEtatLivraison('Effectué');
                $id_com=$livraison->getIdCommande();
                $em->flush();
                foreach ($commandes as $c)
                {
                    if($c->getId()==$id_com)
                    {
                        $c->setEtatCommande('Effectué');
                        $em->flush();
                    }
                }
            }
        }
        foreach ($livreur as $l)
        {
            if($l->getId()==$id_l)
            {
                $l->setDisponibilite('Disponible');

                $l->setNbrLivraison($l->getNbrLivraison()+1);
                $em->flush();
            }
        }

        $livraison = $em->getRepository('EcoBundle:Livraison')->findAll();
        $users = $em->getRepository('EcoBundle:User')->findAll();
        $commandes = $em->getRepository('EcoBundle:Commande')->findAll();
        return $this->render('@Eco/DashboardUser/da_page_index_livreur.html.twig', array(
            'users'=> $users,'livraison'=>$livraison,'commandes'=>$commandes));
    }
}