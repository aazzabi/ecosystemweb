<?php
/**
 * Created by PhpStorm.
 * User: Aziz
 * Date: 22/02/2019
 * Time: 13:02
 */
/**
 *
 * @Route("commande")
 */

namespace EcoBundle\Controller\Front;


use EcoBundle\Entity\Commande;
use EcoBundle\Entity\Livraison;
use EcoBundle\Entity\LigneCommande;
use EcoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use EcoBundle\Entity\AnnoncePanier;
use EcoBundle\Entity\Annonce;

use EcoBundle\Entity\CategorieAnnonce;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;

class CommandeController extends Controller
{
    /**
     * Finds and displays a user entity.
     *
     * @Route("/new/{prix_total}/{id_u}", name="ajout_commande")
     * @Method("GET")
     */
    public function newAction($prix_total,$id_u)
    {

        $em =$this->getDoctrine()->getManager();

         $liste_panier = $em->getRepository('EcoBundle:AnnoncePanier')->findAll();

         $commande=new Commande();
         $etat="En cours";
         $date_auj=new \DateTime('now');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $commande->setCommandePassedBy($user);
        $id_u=$user->getId();
        $commande->setIdUtilisateur($id_u);
         $commande->setPrixTotal($prix_total);
        $commande->setDateEmission($date_auj);
         $commande->setEtatCommande($etat);
         $em->persist($commande);
         $em->flush();
         $id_com=$commande->getId();


        foreach ($liste_panier as $article_panier)
         {
             $Ligne_commande=new LigneCommande();
             $prix=$article_panier->getPrix();
             $id_annonce=$article_panier->getIdAnnonce();
             $Ligne_commande->setIdAnnonce($id_annonce);
             $Ligne_commande->setPrixAnnonce($prix);
             $Ligne_commande->setIdUtilisateur($id_u);
             $Ligne_commande->setIdCommande($id_com);

             $em->persist($Ligne_commande);
             $em->flush();
             $com_modif = $em->getRepository(Commande::class)->find($id_com);

         }

         $L=$em->getRepository(AnnoncePanier::class)->findAll();
         foreach ($L as $value)
         {
             $em->remove($value);
             $em->flush();
         }
        $commandes = $em->getRepository('EcoBundle:Commande')->findAll();
        $lignes = $em->getRepository('EcoBundle:LigneCommande')->findAll();
            $annonces = $em->getRepository('EcoBundle:Annonce')->findAll();
         return $this->render('@Eco/DashboardUser/page_index_commande.html.twig',array(
             'commandes'=> $commandes,'lignes'=> $lignes,'annonces'=> $annonces));



        }
    /**
     * Finds and displays a user entity.
     *
     * @Route("/show_ligne/{id_c}", name="ligne_show")
     * @Method("GET")
     */
    public function showLigneAction($id_c)
    {
        $em =$this->getDoctrine()->getManager();
        $L=$em->getRepository(AnnoncePanier::class)->findAll();

        $annonces = $em->getRepository('EcoBundle:Annonce')->findAll();
        $liste_ligne = $em->getRepository('EcoBundle:LigneCommande')->findAll();
        $tab=array('id_commande' => $id_c);

        return $this->render('@Eco/DashboardUser/detail_commande.html.twig',array(
           'liste_ligne'=> $liste_ligne,'annonces'=>$annonces,'tab'=>$tab));


    }

    /**
     * @Route("/annuler_commande/{id_c}", name="annuler_commande")
     * @Method("GET")
     */
    public function annulerAction($id_c)
    {
        $em=$this->getDoctrine()->getManager();
        $commandes = $em->getRepository('EcoBundle:Commande')->findAll();
        $lignes = $em->getRepository('EcoBundle:LigneCommande')->findAll();
        foreach ($commandes as $value)
        {
            if($value->getId()==$id_c)
            {
                $date_com=$value->getDateEmission();
                $DateNow = new \DateTime('now');;
                $TempsRestant = $DateNow->diff($date_com);
                if($TempsRestant->h >24 )
                {
                    echo "<script language=\"javascript\">alert(\"Vous ne pouvez pas annuler la commande car avez dépassé les 24 heures  \");</script>";

                }
                else
                {
                    foreach ($lignes as $v)
                    {
                        if($v->getIdCommande()==$id_c)
                        {
                            $em->remove($v);
                            $em->flush();
                        }
                    }
                    $em->remove($value);
                    $em->flush();

                }
            }

        }
        $commandes = $em->getRepository('EcoBundle:Commande')->findAll();
        return $this->render('@Eco/DashboardUser/page_index_commande.html.twig', array(
            'commandes'=> $commandes
        ));

        /*
        foreach ($commandes as $value)
        {
            if($value->getId()==$id_c)
            {
                foreach ($lignes as $v)
                {
                    if($v->getIdCommande()==$id_c)
                    {
                        $em->remove($v);
                        $em->flush();
                    }
                }
                $em->remove($value);
                $em->flush();
            }
        }*/


    }

    /**
     * @Route("/show_commande", name="show_commande")
     * @Method("GET")
     */
    public function showAction()
    {
        $em=$this->getDoctrine()->getManager();
        $commandes = $em->getRepository('EcoBundle:Commande')->findAll();
        return $this->render('@Eco/DashboardUser/page_index_commande.html.twig', array(
            'commandes'=> $commandes
        ));
    }





    }

