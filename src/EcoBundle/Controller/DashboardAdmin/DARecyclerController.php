<?php
/**
 * Created by PhpStorm.
 * User: Mehdi
 * Date: 14/02/2019
 * Time: 22:04
 */

namespace EcoBundle\Controller\DashboardAdmin;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
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
        $collectChart->getOptions()->setTitle('Statistiques par rapport au représentant de société et leurs Points de collectes');
        $collectChart->getOptions()->setHeight(500);
        $collectChart->getOptions()->setWidth(900);
        $collectChart->getOptions()->getTitleTextStyle()->setBold(true);
        $collectChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $collectChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $collectChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $collectChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        $missionChart = new PieChart();
        $missionChart->getData()->setArrayToDataTable(
            [['Task', 'Hours per Day'],
             ['Work', 11],
             ['Eat', 2],
             ['Commute', 2],
             ['Watch TV', 2],
             ['Sleep', 7]
            ]
        );
        $missionChart->getOptions()->setTitle('My Daily Activities');
        $missionChart->getOptions()->setHeight(500);
        $missionChart->getOptions()->setWidth(900);
        $missionChart->getOptions()->getTitleTextStyle()->setBold(true);
        $missionChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $collectChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $missionChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $missionChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('@Eco/DashboardAdmin/Missions/indexEvent.html.twig', array(
            'recys' => $recys,
            'evenement' => $evenement,
            'collectChart' => $collectChart,
            'missionChart' => $missionChart
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