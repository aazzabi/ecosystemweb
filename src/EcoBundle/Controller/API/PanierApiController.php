<?php
/**
 * Created by PhpStorm.
 * User: Aziz
 * Date: 01/05/2019
 * Time: 00:34
 */

namespace EcoBundle\Controller\API;


use EcoBundle\Entity\AnnoncePanier;
use EcoBundle\Entity\Commande;
use EcoBundle\Entity\Livraison;
use EcoBundle\Entity\Livreur;
use EcoBundle\Entity\LigneCommande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EcoBundle\Entity\Annonce;
use EcoBundle\Entity\CategorieAnnonce;
use EcoBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


/**
 *
 * @Route("panierApi")
 */

class PanierApiController extends Controller
{


    /**
     * @Route("/new", name="panier_api_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $annoncep = new AnnoncePanier();
        $annoncep->setIdAnnonce($request->get('id_annonce'));
        $annoncep->setTitre($request->get('titre'));
        $annoncep->setDescription($request->get('description'));
        $annoncep->setPrix($request->get('prix'));
        $annoncep->setRegion($request->get('region'));
        $annoncep->setPhoto($request->get('photo'));
        $em->persist($annoncep);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($annoncep);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/", name="getAll_annoncePanier")
     */
    public function getall()
    {
        $task = $this->getDoctrine()->getManager()
            ->getRepository(AnnoncePanier::class)
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/vider", name="panier_api_vider")
     * @Method({"GET", "POST"})
     */
    public function viderAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $L=$em->getRepository(AnnoncePanier::class)->findAll();
        foreach ($L as $value)
        {
            $em->remove($value);
            $em->flush();
        }

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($L, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/livencours", name="livencours2")
     * @Method({"GET", "POST"})
     */
    public function livencoursAction(Request $request)
    {
        $id_c=$request->get('id_c');
        $em=$this->getDoctrine()->getManager();
        $L=$em->getRepository(Commande::class)->findAll();
        foreach ($L as $value)
        {
            if($value->getId()==$id_c)
            {
                $value->setEtatCommande('En cours de livraison');
                $em->flush();
            }

        }

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($L, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/livlivre", name="livencours3")
     * @Method({"GET", "POST"})
     */
    public function livlivre(Request $request)
    {
        $id_c=$request->get('id_liv');
        $em=$this->getDoctrine()->getManager();
        $L=$em->getRepository(Livraison::class)->findAll();
        foreach ($L as $value)
        {
            if($value->getId()==$id_c)
            {
                $value->setEtatLivraison('Effectué');
                $em->flush();
            }

        }

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($L, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/delete_a", name="panier_api_supp")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request)
    {

       $id=$request->get('id');
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
        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($annonce_panier, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/deletecmd", name="panier_api_suppcmd")
     * @Method({"GET", "POST"})
     */
    public function deleteCommande(Request $request)
    {

        $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $annonce_panier = $em->getRepository('EcoBundle:LigneCommande')->findAll();
        $annonce_panier2= $em->getRepository('EcoBundle:Commande')->findAll();
        foreach ($annonce_panier as $p)
        {
            if($p->getIdCommande()==$id)
            {
                $em->remove($p);
                $em->flush();
            }
        }

        foreach ($annonce_panier2 as $y)
        {
            if($y->getId()==$id)
            {
                $em->remove($y);
                $em->flush();
            }
        }


        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($annonce_panier, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     * @Route("/newcmd", name="panier_api_newcmd")
     * @Method({"GET", "POST"})
     */
    public function newCMDAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $commande=new Commande();
        $date_auj=new \DateTime('now');
        $commande->setIdUtilisateur($request->get('id_utilisateur'));
        $id_u1=$commande->getIdUtilisateur();
        $commande->setPrixTotal($request->get('prix_total'));
        $commande->setDateEmission($date_auj);
        $commande->setEtatCommande($request->get('etat_commande'));
        $em->persist($commande);
        $em->flush();

        $id_com=$commande->getId();
        $id_u=$commande->getIdUtilisateur();
        $liste_panier = $em->getRepository('EcoBundle:AnnoncePanier')->findAll();

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


        }

        $L=$em->getRepository(AnnoncePanier::class)->findAll();
        foreach ($L as $value)
        {
            $em->remove($value);
            $em->flush();
        }
        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($L, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/getcmd", name="getAll_commandes")
     */
    public function getall2()
    {
        $task = $this->getDoctrine()->getManager()
            ->getRepository(Commande::class)
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/getcmdclient", name="getcmdClient")
     */
    public function getcmdclient(Request $request)
    {
        $id=$request->get('id_user');
        $task = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Commande')
            ->findBy(array('idUtilisateur'=> $id));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/getlivclient", name="getlivClient")
     */
    public function getlivclient(Request $request)
    {
        $id=$request->get('id_user');
        $task = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Livraison')
            ->findBy(array('idUtilisateur'=> $id));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/getlivlivreur", name="getlivLivreur")
     */
    public function getlivLivreur(Request $request)
    {
        $id=$request->get('id_livreur');
        $task = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Livraison')
            ->findBy(array('idLivreur'=> $id));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/changeretat", name="changerEtat")
     */
    public function changeretat(Request $request)
    {
        $id_l=$request->get('id_livreur');
        $id_liv=$request->get('id_livraison');

        $em=$this->getDoctrine()->getManager();
        $livraison = $em->getRepository('EcoBundle:Livraison')->findAll();
        $ligne = $em->getRepository('EcoBundle:LigneCommande')->findAll();
        $annonce = $em->getRepository('EcoBundle:Annonce')->findAll();
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
                        foreach ($ligne as $l)
                        {
                            if($l->getIdCommande()==$id_com)
                            {
                                $id_a=$l->getIdAnnonce();
                                foreach ($annonce as $a)
                                {
                                    if($a->getId()==$id_a)
                                    {
                                        $em->remove($a);
                                        $em->flush();
                                    }
                                }
                            }
                        }

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




    }



    /**
     * @Route("/getliv", name="getAll_livraisons")
     */
    public function getall3()
    {

        $task = $this->getDoctrine()->getManager()
            ->getRepository(Livraison::class)
            ->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/getligne", name="getAllLignes")
     */
    public function getLignes()
    {

        $task = $this->getDoctrine()->getManager()
            ->getRepository(LigneCommande::class)
            ->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }



    /**
     * @Route("/livraison", name="panier_api_newliv")
     * @Method({"GET", "POST"})
     */
    public function newLivAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $livreurs = $em->getRepository('EcoBundle:Livreur')->findAll();
        foreach ($livreurs as $l)
        {
            if ($l->getDisponibilite() == 'Disponible')
            {


                $id_liv = $l->getId();


            }

        }
        $livraison = new Livraison();
        $livraison->setIdCommande($request->get('id_cmd'));
        $livraison->setIdLivreur($id_liv);
        $livraison->setIdUtilisateur($request->get('id_u'));
        $livraison->setEtatLivraison("En cours");
        $date_com=new \DateTime('now');
        $livraison->setDateLivraison($date_com->modify('+4 day'));
        $livraison->setAdresseComplete($request->get('adresse'));
        $livraison->setVilleLivraison($request->get('ville'));
        $livraison->setLivraisonPassedBy($request->get('current_user'));
        $livraison->setCodeLivraison($request->get('code_livraison'));
        $em->persist($livraison);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($livraison);
        return new JsonResponse($formatted);
    }





}