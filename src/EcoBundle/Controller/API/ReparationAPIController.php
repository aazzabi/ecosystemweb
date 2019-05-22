<?php

namespace EcoBundle\Controller\API;

use EcoBundle\Entity\Annonce;
use EcoBundle\Entity\AnnonceRep;
use EcoBundle\Entity\CategorieAnnonce;
use EcoBundle\Entity\Reparation;
use EcoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 *
 * @Route("repapi")
 */
class ReparationAPIController extends Controller
{
    /**
     * @Route("/all", name="getAll_annoncerep")
     * @Method({"GET", "POST"})
     */
    public function getallAction()
    {
        $task = $this->getDoctrine()->getManager()
            ->getRepository(AnnonceRep::class)
            ->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }
    /**
     * @Route("/reparationUser/id/{userid}/type/{type}", name="get_rep_ByUser")
     * @Method({"GET", "POST"})
     */
    public function getRepByUserAction($userid,$type)
    {
        if ($type==1)
        {
        $task = $this->getDoctrine()->getManager()
            ->getRepository(Reparation::class)
            ->findBy(array('utilisateur'=> $userid));
        }
        else
        {
            $task = $this->getDoctrine()->getManager()
                ->getRepository(Reparation::class)
                ->findBy(array('reparateur'=> $userid));
        }
        

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/valider/id/{userid}", name="Valider_rep")
     * @Method({"GET", "POST"})
     */
    public function validerOffreAction($userid)

    {
        $announcerep = $this->getDoctrine()->getManager()->getRepository('EcoBundle:AnnonceRep') ->find($userid);

        $reparation = new Reparation();
        $reparation->setUtilisateur($announcerep->getUtilisateur());
        $reparation->setReparateur($announcerep->getReparateur());
        $reparation->setCommentaire("  Numéro téléphone Client : ".$announcerep->getUtilisateur()->getNumtel()."  Numéro Réparateur : ". $announcerep->getReparateur()->getNumeroFix()." Prix :".$announcerep->getLastprix());
        $this->getDoctrine()->getManager()->remove($announcerep);
        $this->getDoctrine()->getManager()->persist($reparation);
        $this->getDoctrine()->getManager()->flush();



    }

    /**
     * @Route("/supprimerrep/id/{userid}", name="Supprimer_rep")
     * @Method({"GET", "POST"})
     */
    public function supprimerRepAction($userid)

    {
        $reparation = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Reparation') ->find($userid);
        $this->getDoctrine()->getManager()->remove($reparation);
        $this->getDoctrine()->getManager()->flush();



    }

    /**
     * @Route("/annulerep/id/{userid}", name="Annuler_rep")
     * @Method({"GET", "POST"})
     */

    public function annulerRepAction($userid)

    {
        $reparation = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Reparation') ->find($userid);
        $reparation->setStatut("Annuler");
        $reparation->setCommentaire("Cause inconnue veuillez contacter le réparateur pour en savoir plus");
        $this->getDoctrine()->getManager()->flush();
    }

    /**
     * @Route("/confirmerrep/id/{userid}", name="Confirmer_rep")
     * @Method({"GET", "POST"})
     */

    public function ConfirmerRepAction($userid)

    {
        $reparation = $this->getDoctrine()->getManager()->getRepository('EcoBundle:Reparation') ->find($userid);
        $reparation->setStatut("Terminer");
        $reparation->setCommentaire("Veuillez venir dans le local du réparateur pour récupérer votre objet");
        $this->getDoctrine()->getManager()->flush();
    }

    /**
     * @Route("/ajoutann", name="ajoutAnn_rep")
     * @Method({"GET", "POST"})
     */

    public function ajouterAnnonceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idUtilisateur = $request->get("UserId");
        $Utilisateur = $em->getRepository("EcoBundle:User")->find($idUtilisateur);




        $titre = $request->get("titre");
        $description = $request->get("description");
        $categorie = $request->get("categorie");





        if ($request->files->get("photoannonce") != null) {
            $file = $request->files->get("photoannonce");
            $fileName = $file->getClientOriginalName();
            $file->move(
                $this->getParameter('annoncerep_photo'),
                $fileName
            );
            $file = $request->files->get("photoannonce");
        }
        $annonce = new AnnonceRep();
        $annonce->setTitre($titre);
        $annonce->setDescription($description);
        $annonce->setCategorie(urldecode($categorie));
        $annonce->setPhoto(urldecode($fileName));


        $annonce->setUtilisateur($Utilisateur);

        try {
            $em->persist($annonce);
            $em->flush();
            return new Response("success");

        } catch (Exception $ex) {
            return new Response("fail");
        }

    }



















//    public function getAnnonceById($id)
//    {
//        $ann = $this->getDoctrine()->getManager()->getRepository(Annonce::class)->find($id);
//        $serializer = new Serializer([new ObjectNormalizer()]);
//        $formatted = $serializer->normalize($ann);
//        return new JsonResponse($formatted);
//    }
//    /**
//     * @Route("/annonce/{text}", name="annonceBytext")
//     */
//    public function getAnnonceAction($text)
//    {
//        $ann = $this->getDoctrine()->getManager()
//            ->getRepository(Annonce::class)
//            ->RechercheTitreAnnonce($text);
//        //dump($ann);exit();
//        $serializer = $this->get('jms_serializer');
//
//        $serializer = new Serializer([new ObjectNormalizer()]);
//        $formatted = $serializer->normalize($ann);
//        return new JsonResponse($formatted);
//    }
//
//    /**
//     * @Route("/new", name="annoce_api_new")
//     * @Method({"GET", "POST"})
//     */
//    public function newAction(Request $request)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $annonce = new Annonce();
//        $annonce->setTitre($request->get('titre'));
//        $annonce->setDescription($request->get('description'));
//        $annonce->setPrix($request->get('prix'));
//        $annonce->setEtat('Disponible');
//        $annonce->setRegion($request->get('region'));
//        $annonce->setLikes(0);
//        $annonce->setViews(0);
//        $annonce->setPhoto($request->get('photo'));
//        $categorie = $em->getRepository(CategorieAnnonce::class)->find($request->get('categorie'));
//        $user = $em->getRepository(User::class)->find($request->get('user'));
//        $annonce->setCategorie($categorie);
//        $annonce->setUser($user);
//
//        $em->persist($annonce);
//        $em->flush();
//
//        $serializer = new Serializer([new ObjectNormalizer()]);
//        $formatted = $serializer->normalize($annonce);
//        return new JsonResponse($formatted);
//    }

    /**
     * @Route("/updatePrix/id/{id}/prix/{prix}/repid/{repid}", name="rep_api_updateprix")
     * @Method({"GET", "POST"})
     */
    public function updatePrixAction(Request $request,$id,$prix,$repid){
        $em = $this->getDoctrine()->getManager();
        $ann = $em->getRepository('EcoBundle:AnnonceRep')->find(array('id'=> $id));
        $reps = $em->getRepository('EcoBundle:User')->findBy(array('id'=> $repid));
        $rep=$reps[0];



        if($ann == null) return new JsonResponse(null);
        else{
            $ann->setLastprix($prix);
            $ann->setReparateur($rep);
            $this->getDoctrine()->getManager()->flush();

                $serializer = new Serializer([new ObjectNormalizer()]);
                return new JsonResponse($serializer->normalize($ann));
            }
        }
    }

