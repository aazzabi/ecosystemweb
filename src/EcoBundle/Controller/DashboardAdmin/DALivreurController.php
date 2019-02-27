<?php
/**
 * Created by PhpStorm.
 * User: Aziz
 * Date: 27/02/2019
 * Time: 02:10
 */

namespace EcoBundle\Controller\DashboardAdmin;
/**
 *
 * @Route("da")
 */
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
use Symfony\Component\Config\Definition\Exception\Exception;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DALivreurController extends Controller
{
    /**
     *
     * @Route("/", name="livreur_note")
     * @Method("GET")
     */
    public function showAction()
    {
       $em=$this->getDoctrine()->getManager();

        $livreur = $em->getRepository('EcoBundle:Livreur')->findAll();

        return $this->render('@Eco/DashboardAdmin/Livraison/da_page_noter_livreur.html.twig', array(
            'livreur'=> $livreur));
    }

    /**
     *
     * @Route("/noter_livreur/{note}/{id_l}", name="noter_livreur")
     * @Method("GET")
     */
    public function noterAction($note,$id_l)
    {
        $em=$this->getDoctrine()->getManager();

        $livreur = $em->getRepository('EcoBundle:Livreur')->findAll();
        foreach ($livreur as $liv)
        {
            if($liv->getId()==$id_l)
            {
                $liv->setNote($note);
                $em->flush();
                //partie mail pour notifier le livreur
            }

        }

        $livreur = $em->getRepository('EcoBundle:Livreur')->findAll();

        return $this->render('@Eco/DashboardAdmin/Livraison/da_page_noter_livreur.html.twig', array(
            'livreur'=> $livreur));
    }
}