<?php
/**
 * Created by PhpStorm.
 * User: Aziz
 * Date: 18/02/2019
 * Time: 14:49
 */

namespace EcoBundle\Controller\Front;


/**
 *
 * @Route("panier")
 */

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use EcoBundle\Entity\AnnoncePanier;
use EcoBundle\Entity\Annonce;

use EcoBundle\Entity\CategorieAnnonce;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;

class PanierController extends Controller
{
  public $i=0;

    /**
     * Finds and displays a user entity.
     *
     * @Route("/", name="panier_show")
     * @Method("GET")
     */
    public function showAction()
    {
        $em =$this->getDoctrine()->getManager();
        $liste = $em->getRepository('EcoBundle:AnnoncePanier')->findAll();

        $prixtotal=0;
        $somme=0;
        foreach ($liste as $value)
        {
            $prixtotal=$value->getPrix();
            $somme=$prixtotal+$somme;
        }

        $tab=array("somme"=>$somme);

        return $this->render('@Eco/Panier/page_index_panier.html.twig', array(
           'liste'=> $liste,'tab'=> $tab));

    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/show2", name="annonce_show")
     * @Method("GET")
     */
    public function show2Action()
    {

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
        $annnonce = $em->getRepository('EcoBundle:Annonce')->findAll();


        return $this->render('@Eco/Front/Annonce/annonce.html.twig', array(
            "annonces"=>$annnonce,'categories'=> $categories,
        ));

    }


    /**
     * Finds and displays a user entity.
     *
     * @Route("/new/{id_c}/{id_a}", name="ajout_annonce")
     * @Method("GET")
     */
    public function newAction($id_c,$id_a)
    {
        $em =$this->getDoctrine()->getManager();
        $lignes = $em->getRepository('EcoBundle:LigneCommande')->findAll();
        foreach ($lignes as $value)
        {
            if($value->getIdAnnonce()==$id_a)
            {
                echo "<script language=\"javascript\">alert(\"Vous ne pouvez pas Ajouter une annonce déja passé en commande \");</script>";

                $em = $this->getDoctrine()->getManager();
                $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
                $annnonce = $em->getRepository('EcoBundle:Annonce')->findAll();
                $liste = $em->getRepository('EcoBundle:AnnoncePanier')->findAll();

                return $this->render('@Eco/Front/Annonce/annonce.html.twig', array(
                    "annonces"=>$annnonce,'categories'=> $categories,'liste'=> $liste,
                ));

            }
        }
        try{
            $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
            $annnonce = $em->getRepository('EcoBundle:Annonce')->findAll();
            $a = $em->getRepository('EcoBundle:Annonce')->find($id_a);
            $prix=$a->getPrix();
            $titre=$a->getTitre();
            $region=$a->getRegion();
            $description=$a->getDescription();
            $photo=$a->getPhoto();

            $L=new AnnoncePanier();
            $L->setIdAnnonce($id_a);
            $L->setPrix($prix);
            $L->setTitre($titre);
            $L->setDescription($description);
            $L->setRegion($region);
            $L->setPhoto($photo);

            $em->persist($L);
            $em->flush();

            $liste = $em->getRepository('EcoBundle:AnnoncePanier')->findAll();

            return $this->render('@Eco/Front/Annonce/annonce.html.twig', array(
                "annonces"=>$annnonce,'categories'=> $categories,'liste'=> $liste,
            ));

        }
        catch (UniqueConstraintViolationException $e)
        {
            echo "<script language=\"javascript\">alert(\"Annonce Déja Ajouté au Panier \");</script>";

            $em = $this->getDoctrine()->getManager();
            $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
            $annnonce = $em->getRepository('EcoBundle:Annonce')->findAll();
            $liste = $em->getRepository('EcoBundle:AnnoncePanier')->findAll();

            return $this->render('@Eco/Front/Annonce/annonce.html.twig', array(
                "annonces"=>$annnonce,'categories'=> $categories,'liste'=> $liste,
            ));

        }

    }


    /**
     * @Route("/show3", name="annonce_show3")
     * @Method("GET")
     */
    public function indexTestAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('EcoBundle:CategorieAnnonce')->findAll();
        $annnonce = $em->getRepository('EcoBundle:Annonce')->findAll();
        $liste = $em->getRepository('EcoBundle:AnnoncePanier')->findAll();

        return $this->render('@Eco/Front/Annonce/annonce.html.twig', array(
            "annonces"=>$annnonce,'categories'=> $categories,'liste'=> $liste,
        ));
    }

    /**
     * @Route("/suprimer/{id}", name="supprimer_ligne")
     * @Method("GET")
     */
    public function supprimerAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $annonce_panier = $em->getRepository('EcoBundle:AnnoncePanier')->findAll();
        foreach ($annonce_panier as $p)
        {
            if($p->getId()==$id)
            {
                $em->remove($p);
                $em->flush();
            }
        }


        $liste = $em->getRepository('EcoBundle:AnnoncePanier')->findAll();

        return $this->render('@Eco/Panier/page_index_panier.html.twig', array(
            'liste'=> $liste
        ));
    }

    /**
     * @Route("/vider_panier", name="vider_panier")
     * @Method("GET")
     */
    public function viderAction()
    {
        $em=$this->getDoctrine()->getManager();
        $L=$em->getRepository(AnnoncePanier::class)->findAll();
        foreach ($L as $value)
        {
            $em->remove($value);
            $em->flush();
        }
        $liste = $em->getRepository('EcoBundle:AnnoncePanier')->findAll();

        return $this->render('@Eco/Panier/page_index_panier.html.twig', array(
            'liste'=> $liste
        ));
    }





}