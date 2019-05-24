<?php

namespace EcoBundle\Controller\API;

use EcoBundle\Entity\Annonce;
use EcoBundle\Entity\CategorieAnnonce;

use EcoBundle\Entity\SignalAnnonce;

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
 * @Route("annonceApi")
 */
class AnnonceApiController extends Controller
{
    /**
     * @Route("/", name="getAll_annonce")
     */
    public function getall()
    {
        $task = $this->getDoctrine()->getManager()
            ->getRepository(Annonce::class)
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/categories") , name="Categorie_api_all")
     */
    public function getAllCategorieAction()
    {
        $cat = $this->getDoctrine()->getManager()->getRepository(CategorieAnnonce::class)->findAll();
        $serializer = $this->get('jms_serializer');

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($cat);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/annonce/{id}") , name="find_api_annonce")
     */
    public function getAnnonceById($id)
    {
        $ann = $this->getDoctrine()->getManager()->getRepository(Annonce::class)->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($ann);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/annonce/{text}", name="annonceBytext")
     */
    public function getAnnonceAction($text)
    {
        $ann = $this->getDoctrine()->getManager()
            ->getRepository(Annonce::class)
            ->RechercheTitreAnnonce($text);
        //dump($ann);exit();
        $serializer = $this->get('jms_serializer');

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($ann);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/new", name="annoce_api_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $annonce = new Annonce();
        $idUtilisateur  = $request->get('user');
        $user = $em->getRepository(User::class)->find($idUtilisateur);

        $titre = $request->get('titre');
        $description = $request->get('description');
        $categorie = $request->get('categorie');
        $categorie = $em->getRepository(CategorieAnnonce::class)->find($categorie);
        $prix = $request->get("prix");
        $region = $request->get('region');
        if ($request->files->get("photoannonce") != null) {
            $file = $request->files->get("photoannonce");
            $fileName = $file->getClientOriginalName();
            $file->move(
                $this->getParameter('annonce_photo'),
                $fileName
            );
            $file = $request->files->get("photoannonce");
        }

        $annonce->setTitre($titre);
        $annonce->setDescription($description);
        $annonce->setPrix($prix);
        $annonce->setEtat('Disponible');
        $annonce->setRegion($region);
        $annonce->setLikes(0);
        $annonce->setViews(0);
        $annonce->setNote(0);
        $annonce->setPhoto(urldecode($fileName));
        $annonce->setCategorie($categorie);
        $annonce->setUser($user);

        $em->persist($annonce);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($annonce);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/categorie/{cat}", name="json_cat_annopnce")
     */
    public function RecherchCategorieAction($cat)
    {

        $em = $this->getDoctrine()->getManager();
        $annnonce = new Annonce();
        $annnonce = $em->getRepository('EcoBundle:Annonce')->findBycategorie($cat);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($annnonce);
        return new JsonResponse($formatted);
    }

    /**
     *
     * @Route("/snAnnonce",name="signalAnnonce_json")                                                                                                                                                                                            ")
     * @Method({"GET", "POST"})
     */
    public function SignalAction(Request $request)
    {

        $signal = new SignalAnnonce();
        $em = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository('EcoBundle:Annonce')->find($request->get('idAnnonce'));
        $user = $em->getRepository(User::class)->find($request->get('user'));
        $libRadio = $request->get('description');
        $signal->setDescription($libRadio);
        $signal->setAnnonce($annonce);
        $signal->setUser($user);
        $em->persist($signal);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($signal);
        return new JsonResponse($formatted);
    }


    /**
     *
     * @Route("/signalAnnonce", name="signal_json")                                                                                                                                                                                             ")
     * @Method({"GET", "POST"})
     */
    public function getCountSigna()
    {
        $em = $this->getDoctrine()->getManager();
        $signales = array();
        $RAW_QUERY = 'SELECT *,COUNT(*) as counts FROM signal_annonce GROUP BY annonce_id';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $signales = $statement->fetchAll();
        //dump($signales);exit();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($signales);
        return new JsonResponse($formatted);
    }

    /**
     * Deletes a annonce entity.
     *
     * @Route("/delete/{id}", name="json_dellet_annonce")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAnnonceAction($id)
    {
        $annonce = $this->getDoctrine()->getRepository('EcoBundle:Annonce')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($annonce);
        return new JsonResponse($formatted);
    }


    /**
     * Finds and displays a user entity.
     *
     * @Route("/viwes/{id}", name="f_json_viwe")
     * @Method({"GET", "POST"})
     */
    public function setViweAction($id)
    {
        $annonce = $this->getDoctrine()->getRepository('EcoBundle:Annonce')->find($id);
        $annonce->setViews($annonce->getViews()+1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($annonce);
        return new JsonResponse($formatted);
    }

    /**
     * Creates a new Categorie et annonce entity.
     *
     * @Route("/likes/{id}", name="json_like_annonce")
     * @Method({"GET", "POST"})
     */
    public function newJaimeAction($id)
    {
            $em = $this->getDoctrine()->getManager();
            $annonce = $em->getRepository('EcoBundle:Annonce')->find($id);
            $annonce->setLikes($annonce->getLikes() + 1);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($annonce);
            return new JsonResponse($formatted);
    }
    /**
     * Creates a new Categorie et annonce entity.
     *
     * @Route("/notes/{id}/{note}", name="json_notes_annonce")
     * @Method({"GET", "POST"})
     */
    public function updateNoteAction($id,$note)
    {
        $em = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository('EcoBundle:Annonce')->find($id);
        if($annonce->getNote() ==0)
        {
            $annonce->setNote(($annonce->getNote()+$note));
        }else
        {
            $annonce->setNote(($annonce->getNote()+$note)/2);
        }
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($annonce);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/trier/{val}", name="trier_json")
     *
     */
    public function TrierAction(Request $request)
    {

        $val = $request->get('val');
        //dump($val);exit();
        if ($val == 'PR') {
            $em = $this->getDoctrine()->getManager();

            $annonces = $em->getRepository('EcoBundle:Annonce')->trierPlusRecent();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($annonces);
            return new JsonResponse($formatted);
        } elseif ($val == 'PE') {
            $em = $this->getDoctrine()->getManager();
            $annonces = $em->getRepository('EcoBundle:Annonce')->trierPrixElv();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($annonces);
            return new JsonResponse($formatted);
        } elseif ($val == 'PB') {

            $em = $this->getDoctrine()->getManager();
            $annonces = $em->getRepository('EcoBundle:Annonce')->trierPrixBas();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($annonces);
            return new JsonResponse($formatted);
        }
    }

    /**
     *
     * @Route("/recherche", name="json_recher")
     * @Method({"GET", "POST"})
     */
    public function rechercheAction(Request $request)
    {
        $keyWord = $request->get('keyWord');
        // dump($keyWord);

            $annonce = $this->getDoctrine()->getRepository('EcoBundle:Annonce')->RechercheTitreAnnonce($keyWord);
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($annonce);
            return new JsonResponse($formatted);


    }

}
