<?php

namespace EcoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        // replace this example code with whatever you need
        return $this->render('default/dashboard.html.twig');
    }


    /**
     * @Route("/recyclage", name="recyclage")
     */
    public function recyclageAction(Request $request)
    {

        // replace this example code with whatever you need
        return $this->render('@Eco/recyclage/recyclage.html.twig');
    }

    /**
     * @Route("/missions", name="missions")
     */
    public function missionsAction(Request $request)
    {

        // replace this example code with whatever you need
        return $this->render('@Eco/recyclage/missions.html.twig');
    }

    /**
     * @Route("/appareilrc", name="appareilrc")
     */
    public function appareilrcAction(Request $request)
    {

        // replace this example code with whatever you need
        return $this->render('@Eco/recyclage/appareilrc.html.twig');
    }

    /**
     * @Route("/pointrc", name="pointrc")
     */
    public function pointrcAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@Eco/recyclage/pointrc.html.twig');
    }

    /**
     * @Route("/pdf", name="pdf")
     */
    public function pdfAction(Request $request)
    {

        $snappy = $this->get('knp_snappy.pdf');

        $html = $this->renderView('@Eco/Annonce/annoncePdf.html.twig', array(
            'title' => 'Hello World !'
        ));

        $filename = 'myFirstSnappyPDF';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }









}

