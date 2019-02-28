<?php
/**
 * Created by PhpStorm.
 * User: Mehdi
 * Date: 14/02/2019
 * Time: 22:04
 */

namespace EcoBundle\Controller\DashboardAdmin;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\TableChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\OrgChart;

use EcoBundle\Entity\CategorieMission;
use EcoBundle\Entity\Missions;
use EcoBundle\Entity\Group;
use EcoBundle\Entity\Livreur;
use EcoBundle\Entity\Reparateur;
use EcoBundle\Entity\RespAsso;
use EcoBundle\Entity\RespSoc;
use EcoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @Route("da")
 */
class DARecyclerController extends Controller
{
    /**
     * @Route("/categorieM", name="da_categoriem_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();

        $categoriesMissions = $em->getRepository('EcoBundle:CategorieMission')->findAll();

        return $this->render('@Eco/DashboardAdmin/Missions/index.html.twig', array(

            'categoriesMissions' => $categoriesMissions,

        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/categoriem/new", name="da_categoriem_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $categorieMissions = new CategorieMission();

        $formCateg = $this->createForm('EcoBundle\Form\CategorieMissionType', $categorieMissions);

        $formCateg->handleRequest($request);


        if ($formCateg->isSubmitted() && $formCateg->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorieMissions);
            $em->flush();
            return $this->redirectToRoute('da_categoriem_index');
        }

        return $this->render('@Eco/DashboardAdmin/Missions/new.html.twig', array(
            'CategorieMission' => $categorieMissions,
            // 'formEvt' => $formEvt->createView(),
            'formCateg' => $formCateg->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/categoriem/{id}/edit", name="da_categoriem_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CategorieMission $categorieMission)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $editForm = $this->createForm('EcoBundle\Form\CategorieMissionType', $categorieMission);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('da_categoriem_index', array('id' => $categorieMission->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/Missions/edit.html.twig', array(
            'CategorieMission' => $categorieMission,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/categoriem/{id}", name="da_categoriem_show")
     * @Method("GET")
     */
    public function showAction(CategorieMission $categorieMission)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        return $this->render('@Eco/DashboardAdmin/Missions/show.html.twig', array(
            'CategorieMission' => $categorieMission,
        ));
    }


    /**
     * @Route("/missions", name="da_missions_index")
     * @Method("GET")
     */

    public function indexEventAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();
        $recys = $em->getRepository('EcoBundle:PtCollecte')->findAll();

        $evenement = $em->getRepository('EcoBundle:Missions')->findAll();
        return $this->render('@Eco/DashboardAdmin/Missions/indexEvent.html.twig', array(
            'recys' => $recys,
            'evenement' => $evenement,
        ));

    }

    /**
     * @Route("/missionsStats", name="da_missionsStats_index")
     * @Method("GET")
     */

    public function indexStatsAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $em = $this->getDoctrine()->getManager();
        $recys = $em->getRepository('EcoBundle:PtCollecte')->findAll();
        $evenement = $em->getRepository('EcoBundle:Missions')->findAll();
        $categM = $em->getRepository('EcoBundle:CategorieMission')->findAll();
        $resps = $em->getRepository('EcoBundle:User')->findAll();
        $val = 0;
        $arr = [['Responsable', 'Nombre de  Points collectes'],];
        foreach ($resps as $resp) {
            foreach ($recys as $rec) {
                if ($resp->getUsername() == $rec->getResponsable())
                    $val += 1;
            }
            array_push($arr, [$resp->getUsername(), $val]);
            $val = 0;
        }
        $collectChart = new PieChart();
        $collectChart->getData()->setArrayToDataTable(
            $arr
        );

        $data =  [
            ['Don', '3'],
            ['Collecte de fonds', '2'],
            ['Besoins', '1'],
            ['Compagne de nettoyage', '3'],
            ['Campagne de sensibilisation sur l\'environnement', '2'],
            ['Collecte de fonds', '1'],
        ];

        $table = new TableChart();
        $table->getData()->setArrayToDataTable($data);

        $data2 =  [
            ['Tunis', '2'],
            ['Ariana', '1'],
            ['Jendouba', '1'],
            ['Bizerte', '1'],
            ['Sidi Bouzid', '1'],
            ['Sousse', '3'],
            ['Gafsa',"1"],
                ['Tozeur',"0"],
                    ['Kébili',"0"],
                        ['Tataouine',"0"],
                            ['Médenine',"0"],

        ];

        $org = new OrgChart();
        $org->getData()->setArrayToDataTable($data2);



        return $this->render('@Eco/DashboardAdmin/Missions/indexStats.html.twig', array(
            'recys' => $recys,
            'evenement' => $evenement,
            'collectChart' => $collectChart,
            'table' =>  $table,
            'org' =>  $org,
        ));
    }


    /**
     * Deletes a Reparateur entity.
     *
     * @Route("/missions/delete/{id}", name="da_missions_delete")
     */
    public function deleteEventAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $evenement = $m->getRepository(Missions::class)->find($id);
        $m->remove($evenement);
        $m->flush();

        return $this->redirectToRoute('da_missions_index');
    }


}