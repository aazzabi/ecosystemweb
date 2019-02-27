<?php
/**
 * Created by PhpStorm.
 * User: Aziz
 * Date: 26/02/2019
 * Time: 01:28
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

class DALivraisonController extends Controller
{
    /**
     *
     * @Route("/livraison", name="da_show_livraison")
     * @Method("GET")
     */
    public function showAction()
    {
        $em=$this->getDoctrine()->getManager();
        $livraisons = $em->getRepository('EcoBundle:Livraison')->findAll();
        $commande = $em->getRepository('EcoBundle:Commande')->findAll();
        $livreus = $em->getRepository('EcoBundle:Livreur')->findAll();
        $users = $em->getRepository('EcoBundle:User')->findAll();
        return $this->render('@Eco/DashboardAdmin/Livraison/da_page_livraison.html.twig', array(
            'livraisons'=> $livraisons,'users'=> $users,'commande'=> $commande,'livreurs'=> $livreus
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/livraison_detail/{id_c}", name="livraison_detail")
     * @Method("GET")
     */
    public function detail2Action($id_c)
    {
        $em =$this->getDoctrine()->getManager();
        // $L=$em->getRepository(AnnoncePanier::class)->findAll();

        $annonces = $em->getRepository('EcoBundle:Annonce')->findAll();
        $liste_ligne = $em->getRepository('EcoBundle:LigneCommande')->findAll();
        $tab=array('id_commande' => $id_c);

        return $this->render('@Eco/DashboardAdmin/Livraison/da_detail_livraison.html.twig',array(
            'liste_ligne'=> $liste_ligne,'annonces'=>$annonces,'tab'=>$tab));

    }

    /**
     * @Route("/stat_livraison", name="stat_livraison")
     * @Method("GET")
     */
    public function statAction()
    {
        $em = $this->getDoctrine()->getManager();
        //nb1
        $RAW_QUERY = 'SELECT  COUNT(*) as nb1  from livraison where ville="Ariana";';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $nb1 = $statement->fetch();
        //nb2
        $RAW_QUERY2 = 'SELECT  COUNT(*) as nb2  from livraison where ville="Béja";';
        $statement2 = $em->getConnection()->prepare($RAW_QUERY2);
        $statement2->execute();
        $nb2 = $statement2->fetch();

        //nb3
        $RAW_QUERY3 = 'SELECT  COUNT(*) as nb3  from livraison where ville="Ben Arous";';
        $statement3 = $em->getConnection()->prepare($RAW_QUERY3);
        $statement3->execute();
        $nb3 = $statement2->fetch();

        //nb4
        $RAW_QUERY4 = 'SELECT  COUNT(*) as nb4  from livraison where ville="Bizerte";';
        $statement4 = $em->getConnection()->prepare($RAW_QUERY4);
        $statement4->execute();
        $nb4 = $statement4->fetch();

        //nb5
        $RAW_QUERY5 = 'SELECT  COUNT(*) as nb5  from livraison where ville="Gabes";';
        $statement5 = $em->getConnection()->prepare($RAW_QUERY5);
        $statement5->execute();
        $nb5 = $statement5->fetch();

        //nb6
        $RAW_QUERY6 = 'SELECT  COUNT(*) as nb6  from livraison where ville="Gafsa";';
        $statement6 = $em->getConnection()->prepare($RAW_QUERY6);
        $statement6->execute();
        $nb6 = $statement6->fetch();

        //nb7
        $RAW_QUERY7 = 'SELECT  COUNT(*) as nb7  from livraison where ville="Jendouba";';
        $statement7= $em->getConnection()->prepare($RAW_QUERY7);
        $statement7->execute();
        $nb7 = $statement7->fetch();

        //nb8
        $RAW_QUERY8 = 'SELECT  COUNT(*) as nb8  from livraison where ville="Kairouan";';
        $statement8= $em->getConnection()->prepare($RAW_QUERY8);
        $statement8->execute();
        $nb8 = $statement8->fetch();

        //nb9
        $RAW_QUERY9 = 'SELECT  COUNT(*) as nb9  from livraison where ville="Kasserine";';
        $statement9= $em->getConnection()->prepare($RAW_QUERY9);
        $statement9->execute();
        $nb9 = $statement8->fetch();

        //nb10
        $RAW_QUERY10 = 'SELECT  COUNT(*) as nb10 from livraison where ville="Kébili";';
        $statement10= $em->getConnection()->prepare($RAW_QUERY10);
        $statement10->execute();
        $nb10 = $statement10->fetch();

        //nb11
        $RAW_QUERY11 = 'SELECT  COUNT(*) as nb11  from livraison where ville="Le Kef";';
        $statement11= $em->getConnection()->prepare($RAW_QUERY11);
        $statement11->execute();
        $nb11 = $statement11->fetch();

        //nb12
        $RAW_QUERY12 = 'SELECT  COUNT(*) as nb12  from livraison where ville="Mahdia";';
        $statement12= $em->getConnection()->prepare($RAW_QUERY12);
        $statement12->execute();
        $nb12 = $statement12->fetch();

        $RAW_QUERY13 = 'SELECT  COUNT(*) as nb13  from livraison where ville="La Manouba";';
        $statement13= $em->getConnection()->prepare($RAW_QUERY13);
        $statement13->execute();
        $nb13 = $statement13->fetch();

        $RAW_QUERY14 = 'SELECT  COUNT(*) as nb14  from livraison where ville="Médanine";';
        $statement14= $em->getConnection()->prepare($RAW_QUERY14);
        $statement14->execute();
        $nb14 = $statement14->fetch();

        $RAW_QUERY15 = 'SELECT  COUNT(*) as nb15  from livraison where ville="Monastir";';
        $statement15= $em->getConnection()->prepare($RAW_QUERY15);
        $statement15->execute();
        $nb15 = $statement12->fetch();

        $RAW_QUERY16 = 'SELECT  COUNT(*) as nb16  from livraison where ville="Nabeul";';
        $statement16= $em->getConnection()->prepare($RAW_QUERY16);
        $statement16->execute();
        $nb16 = $statement16->fetch();

        $RAW_QUERY17 = 'SELECT  COUNT(*) as nb17  from livraison where ville="Sfax";';
        $statement17= $em->getConnection()->prepare($RAW_QUERY17);
        $statement17->execute();
        $nb17 = $statement17->fetch();

        $RAW_QUERY18 = 'SELECT  COUNT(*) as nb18  from livraison where ville="Sidi Bouzid";';
        $statement18= $em->getConnection()->prepare($RAW_QUERY18);
        $statement18->execute();
        $nb18 = $statement18->fetch();

        $RAW_QUERY19 = 'SELECT  COUNT(*) as nb19  from livraison where ville="Siliana";';
        $statement19= $em->getConnection()->prepare($RAW_QUERY19);
        $statement19->execute();
        $nb19 = $statement19->fetch();

        $RAW_QUERY20 = 'SELECT  COUNT(*) as nb20  from livraison where ville="Sousse";';
        $statement20= $em->getConnection()->prepare($RAW_QUERY20);
        $statement20->execute();
        $nb20 = $statement20->fetch();

        $RAW_QUERY21 = 'SELECT  COUNT(*) as nb21  from livraison where ville="Tataouine";';
        $statement21= $em->getConnection()->prepare($RAW_QUERY21);
        $statement21->execute();
        $nb21 = $statement21->fetch();

        $RAW_QUERY22 = 'SELECT  COUNT(*) as nb22  from livraison where ville="Tozeur";';
        $statement22= $em->getConnection()->prepare($RAW_QUERY22);
        $statement22->execute();
        $nb22 = $statement22->fetch();

        $RAW_QUERY23 = 'SELECT  COUNT(*) as nb23  from livraison where ville="Tunis";';
        $statement23= $em->getConnection()->prepare($RAW_QUERY23);
        $statement23->execute();
        $nb23= $statement23->fetch();

        $RAW_QUERY24 = 'SELECT  COUNT(*) as nb24  from livraison where ville="Zaghouan";';
        $statement24= $em->getConnection()->prepare($RAW_QUERY24);
        $statement24->execute();
        $nb24 = $statement24->fetch();

        $tab=array();
        $tab[1]=intval($nb1['nb1']);
        $tab[2]=intval($nb2['nb2']);
        $tab[3]=intval($nb3['nb3']);
        $tab[4]=intval($nb4['nb4']);
        $tab[5]=intval($nb5['nb5']);
        $tab[6]=intval($nb6['nb6']);
        $tab[7]=intval($nb7['nb7']);
        $tab[8]=intval($nb8['nb8']);
        $tab[9]=intval($nb9['nb9']);
        $tab[10]=intval($nb10['nb10']);
        $tab[11]=intval($nb11['nb11']);
        $tab[12]=intval($nb12['nb12']);

        $tab[13]=intval($nb13['nb13']);
        $tab[14]=intval($nb14['nb14']);
        $tab[15]=intval($nb15['nb15']);
        $tab[16]=intval($nb16['nb16']);
        $tab[17]=intval($nb17['nb17']);
        $tab[18]=intval($nb18['nb18']);
        $tab[19]=intval($nb19['nb19']);
        $tab[20]=intval($nb20['nb20']);
        $tab[21]=intval($nb21['nb21']);
        $tab[22]=intval($nb22['nb22']);
        $tab[23]=intval($nb23['nb23']);
        $tab[24]=intval($nb24['nb24']);

        //$em = $this->getDoctrine()->getManager();
        return $this->render('@Eco/DashboardAdmin/Livraison/stat_livraison.html.twig',array('tab'=> $tab));

    }


}