<?php
/**
 * Created by PhpStorm.
 * User: Aziz
 * Date: 25/02/2019
 * Time: 16:45
 */

namespace EcoBundle\Controller\Front;
/**
 *
 * @Route("livraison")
 */
use EcoBundle\Entity\Commande;
use EcoBundle\Entity\Livraison;
use EcoBundle\Entity\LigneCommande;
use EcoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EcoBundle\Entity\AnnoncePanier;
use EcoBundle\Entity\Annonce;

use EcoBundle\Entity\CategorieAnnonce;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;


class LivraisonController extends Controller
{
    /**
     * Finds and displays a user entity.
     *
     * @Route("/new/{id_c}/{id_u}/{nom_prop}/{rue_u}/{ville_u}", name="ajout_livraison")
     * @Method("GET")
     */
    public function newAction($id_c,$id_u,$nom_prop,$rue_u,$ville_u)
     {
         $x=0;
         $em =$this->getDoctrine()->getManager();
         $livreurs = $em->getRepository('EcoBundle:Livreur')->findAll();
        // $commandes = $em->getRepository('EcoBundle:Livreur')->findAll();
         $livraisons = $em->getRepository('EcoBundle:Livraison')->findAll();
         foreach ($livraisons as $it)
         {
             if($it->getIdCommande()==$id_c)
             {
                 echo "<script language=\"javascript\" class='foo'>alert(\"Vous avez déja passé une livraison pour cette commande \");</script>";
                 $em=$this->getDoctrine()->getManager();
                 $commandes = $em->getRepository('EcoBundle:Commande')->findAll();
                 return $this->render('@Eco/DashboardUser/page_index_commande.html.twig', array(
                     'commandes'=> $commandes
                 ));
             }
         }

         $cm = $em->getRepository('EcoBundle:Commande')->find($id_c);
         $date_com=$cm->getDateEmission();
         $Livraison=new Livraison();
         $adresse_complete=$nom_prop." ".$rue_u." ".$ville_u;
         $Livraison->setIdUtilisateur($id_u);
         $Livraison->setVilleLivraison($ville_u);
         $Livraison->setAdresseComplete($adresse_complete);
         $Livraison->setIdCommande($id_c);

foreach ($livreurs as $l)
{
        if ($l->getDisponibilite() == 'Disponible')
        {
            if($l->getZone() == $ville_u)
            {

                $id_livreur = $l->getId();

            }

        }


}
/*
if($x!=1)
{
    echo "<script language=\"javascript\" class='foo'>alert(\"Veuillez nous excuser nous avons un manqe d''effectif au niveau des livreurs ,Réessayer plus tard\");</script>";
    $em = $this->getDoctrine()->getManager();
    $commandes = $em->getRepository('EcoBundle:Commande')->findAll();
    return $this->render('@Eco/DashboardUser/page_index_commande.html.twig', array(
        'commandes' => $commandes
    ));
}
*/

         $Livraison->setEtatLivraison('En cours');
         $Livraison->setDateLivraison($date_com->modify('+4 day'));
         $Livraison->setIdLivreur($id_livreur);

         $em->persist($Livraison);
         $em->flush();
//Rendre le livreur Non disponible des livreurs
         $livreurs = $em->getRepository('EcoBundle:Livreur')->findAll();

         foreach ($livreurs as $l)
         {
             if($l->getId()==$id_livreur)
             {
                $l->setDisponibilite('Non Disponible');
                 $em->flush();

             }
         }

         $livraisons = $em->getRepository('EcoBundle:Livraison')->findAll();
         $commandes = $em->getRepository('EcoBundle:Commande')->findAll();
         return $this->render('@Eco/DashboardUser/page_index_livraison.html.twig',array(
             'livraisons'=> $livraisons,'commandes'=> $commandes));
       }

    /**
     * @Route("/show_livraison", name="show_livraison")
     * @Method("GET")
     */
    public function showAction()
    {
        $em=$this->getDoctrine()->getManager();
        $livraisons = $em->getRepository('EcoBundle:Livraison')->findAll();
        $commandes = $em->getRepository('EcoBundle:Commande')->findAll();
        return $this->render('@Eco/DashboardUser/page_index_livraison.html.twig',array(
            'livraisons'=> $livraisons,'commandes'=> $commandes));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/detail_livraison/{id_c}", name="detail_livraison")
     * @Method("GET")
     */
    public function detailAction($id_c)
    {
        $em =$this->getDoctrine()->getManager();
       // $L=$em->getRepository(AnnoncePanier::class)->findAll();

        $annonces = $em->getRepository('EcoBundle:Annonce')->findAll();
        $liste_ligne = $em->getRepository('EcoBundle:LigneCommande')->findAll();
        $tab=array('id_commande' => $id_c);

        return $this->render('@Eco/DashboardUser/detail_livraison.html.twig',array(
            'liste_ligne'=> $liste_ligne,'annonces'=>$annonces,'tab'=>$tab));

    }



}