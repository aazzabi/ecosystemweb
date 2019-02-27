<?php

namespace EcoBundle\Controller\DashboardUser;

use EcoBundle\Entity\Annonce;
use EcoBundle\Entity\SignalAnnonce;
use EcoBundle\Form\RechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;


/**
 *
 * @Route("du")
 */
class DUAnnonceController extends Controller
{

    /**
 * @Route("/annonce", name="du_annonce_index")
 * @Method("GET")
 */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        //$annonces = $user->getMyAnnonces();
//        $em = $this->getDoctrine()->getManager();
        $annonces = $em->getRepository('EcoBundle:Annonce')->findByUser($user);

        //var_dump($annonces);die;

        return $this->render('@Eco/DashboardUser/Annonce/index.html.twig', array(
            'useer' => $user,
            'annonces' => $annonces,
        ));
    }
    /**
     * Creates a new Categorie et annonce entity.
     *
     * @Route("/annonce/new", name="du_annonce_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $annonce = new Annonce();
        $formAnnonce = $this->createForm('EcoBundle\Form\AnnonceType',$annonce);
        $formAnnonce->handleRequest($request);
        if($formAnnonce->isSubmitted() && $formAnnonce->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $annonce->setViews(0);
            $annonce->setLikes(0);
            $annonce->setEtat("Disponible");
            $annonce->setUser($user);
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('du_annonce_index');

        }
        return $this->render('@Eco/DashboardUser/Annonce/new.html.twig', array(
            'formAnnonce' => $formAnnonce->createView()
        ));

    }
    /**
     * Finds and displays a user entity.
     *
     * @Route("/annonce/{id}", name="du_annonce_show")
     * @Method("GET")
     */
    public function showAction(Annonce $annonce)
    {

        return $this->render('@Eco/DashboardUser/Annonce/show.html.twig', array(
            'annonce' => $annonce,
        ));
    }
    /**
     * Displays a form to edit an existing Annonce entity.
     *
     * @Route("/annonce/{id}/editAnnonce", name="du_annonce_edit")
     * @Method({"GET", "POST"})
     */
    public function editAnnonceAction(Request $request, Annonce $annonce)
    {
        $editFormAnn = $this->createForm('EcoBundle\Form\AnnonceType',$annonce);
        $editFormAnn->handleRequest($request);
        if ($editFormAnn->isSubmitted() && $editFormAnn->isValid())
        {
            $annonce->setDateUpdate(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('du_annonce_edit', array('id' => $annonce->getId()));
        }

        return $this->render('@Eco/DashboardUser/Annonce/editAnnonce.html.twig', array(
            'annonce'    => $annonce,
            'formAnn'    => $editFormAnn->createView(),
        ));

    }
    /**
     * Deletes a annonce entity.
     *
     * @Route("/annonce/delete/{id}", name="du_annonce_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAnnonceAction($id)
    {
        $annonce = $this->getDoctrine()->getRepository('EcoBundle:Annonce')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();

        return $this->redirectToRoute('du_annonce_index');
    }
    /**
     * Displays a form to edit an existing Annonce entity.
     *
     * @Route("/statistique", name="du_annonce_stat")
     * @Method({"GET", "POST"})
     */
    public function statAction()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $val = $user->getId();
        $em = $this->getDoctrine()->getManager();
        //nb1
        $RAW_QUERY = 'SELECT  COUNT(*) as nb1 from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-01-01\') AND (date_creation <= \'2019-01-31\');';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue(1,$val);
        $statement->execute();
        $nb1 = $statement->fetch();

        //nb2

        $RAW_QUERY2 = 'SELECT  COUNT(*) as nb2  from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-02-01\') AND (date_creation <= \'2019-02-31\');';
        $statement2 = $em->getConnection()->prepare($RAW_QUERY2);
        $statement2->bindValue(1,$val);
        $statement2->execute();
        $nb2 = $statement2->fetch();

        //nb3
        $RAW_QUERY3 = 'SELECT  COUNT(*) as nb3  from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-03-01\') AND (date_creation <= \'2019-03-31\');';
        $statement3 = $em->getConnection()->prepare($RAW_QUERY3);
        $statement3->bindValue(1,$val);
        $statement3->execute();
        $nb3 = $statement3->fetch();

        //nb4
        $RAW_QUERY4 = 'SELECT  COUNT(*) as nb4  from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-04-01\') AND (date_creation <= \'2019-04-31\');';
        $statement4 = $em->getConnection()->prepare($RAW_QUERY4);
        $statement4->bindValue(1,$val);
        $statement4->execute();
        $nb4 = $statement4->fetch();

        //nb5
        $RAW_QUERY5 = 'SELECT  COUNT(*) as nb5  from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-05-01\') AND (date_creation <= \'2019-05-31\');';
        $statement5 = $em->getConnection()->prepare($RAW_QUERY5);
        $statement5->bindValue(1,$val);
        $statement5->execute();
        $nb5 = $statement5->fetch();

        //nb6
        $RAW_QUERY6 = 'SELECT  COUNT(*) as nb6  from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-06-01\') AND (date_creation <= \'2019-06-31\');';
        $statement6 = $em->getConnection()->prepare($RAW_QUERY6);
        $statement6->bindValue(1,$val);
        $statement6->execute();
        $nb6 = $statement6->fetch();

        //nb7
        $RAW_QUERY7 = 'SELECT  COUNT(*) as nb7  from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-07-01\') AND (date_creation <= \'2019-07-31\');';
        $statement7= $em->getConnection()->prepare($RAW_QUERY7);
        $statement7->bindValue(1,$val);
        $statement7->execute();
        $nb7 = $statement7->fetch();

        //nb8
        $RAW_QUERY8 = 'SELECT  COUNT(*) as nb8  from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-08-01\') AND (date_creation <= \'2019-08-31\');';
        $statement8= $em->getConnection()->prepare($RAW_QUERY8);
        $statement8->bindValue(1,$val);
        $statement8->execute();
        $nb8 = $statement8->fetch();

        //nb9
        $RAW_QUERY9 = 'SELECT  COUNT(*) as nb9  from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-09-01\') AND (date_creation <= \'2019-09-31\');';
        $statement9= $em->getConnection()->prepare($RAW_QUERY9);
        $statement9->bindValue(1,$val);
        $statement9->execute();
        $nb9 = $statement8->fetch();

        //nb10
        $RAW_QUERY10 = 'SELECT  COUNT(*) as nb10  from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-10-01\') AND (date_creation <= \'2019-10-31\');';
        $statement10= $em->getConnection()->prepare($RAW_QUERY10);
        $statement10->bindValue(1,$val);
        $statement10->execute();
        $nb10 = $statement10->fetch();

        //nb11
        $RAW_QUERY11 = 'SELECT  COUNT(*) as nb11  from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-11-01\') AND (date_creation <= \'2019-11-31\');';
        $statement11= $em->getConnection()->prepare($RAW_QUERY11);
        $statement11->bindValue(1,$val);
        $statement11->execute();
        $nb11 = $statement11->fetch();

        //nb12
        $RAW_QUERY12 = 'SELECT  COUNT(*) as nb12  from annonce where user_id =? AND likes >=5 AND views >=5 AND (date_creation >= \'2019-12-01\') AND (date_creation <= \'2019-12-31\');';
        $statement12= $em->getConnection()->prepare($RAW_QUERY12);
        $statement12->bindValue(1,$val);
        $statement12->execute();
        $nb12 = $statement12->fetch();

        $jan=intval($nb1['nb1']);
        $fev=intval($nb2['nb2']);
        $mars=intval($nb3['nb3']);
        $avril=intval($nb4['nb4']);
        $mai=intval($nb5['nb5']);
        $juin=intval($nb6['nb6']);
        $jui=intval($nb7['nb7']);
        $aout=intval($nb8['nb8']);
        $sep=intval($nb9['nb9']);
        $oct=intval($nb10['nb10']);
        $nov=intval($nb11['nb11']);
        $dec=intval($nb12['nb12']);


        $tab=array();
        $tab[1]=$jan;
        $tab[2]=$fev;
        $tab[3]=$mars;
        $tab[4]=$avril;
        $tab[5]=$mai;
        $tab[6]=$juin;
        $tab[7]=$jui;
        $tab[8]=$aout;
        $tab[9]=$sep;
        $tab[10]=$oct;
        $tab[11]=$nov;
        $tab[12]=$dec;

        return $this->render('@Eco/DashboardUser/Annonce/stat_annonce.html.twig',array('tab'=> $tab));

    }
}
