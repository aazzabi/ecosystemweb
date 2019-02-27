<?php

namespace EcoBundle\Controller;

use Swift_Attachment;
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

        $em = $this->getDoctrine()->getManager();
        $likes = $em->getRepository('EcoBundle:Annonce')->likeAnnonce();

//        return $this->render('default/index.html.twig', [
//            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
//        ]);
        return $this->render
        ('default/index.html.twig', array(
                'likes' => $likes,
            ));
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        return $this->render('default/dashboard.html.twig');
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

    /**
     * @Route("/email", name="email")
     */
    public function envoyerTicketAction(Request $request)
    {
      $message = \Swift_Message::newInstance()
                ->setSubject('Hello Email')
                ->setFrom('pidevmailer2019@gmail.com')
                ->setTo('arafet.azzabi@gmail.com')
//                ->setBody(
//                    $this->renderView(
//                        // app/Resources/views/Emails/registration.html.twig
//                        'Emails/registration.html.twig',
//                        array('name' => $name)
//                    ),
//                    'text/html'
//                )
                /*
                 * If you also want to include a plaintext version of the message
                ->addPart(
                    $this->renderView(
                        'Emails/registration.txt.twig',
                        array('name' => $name)
                    ),
                    'text/plain'
                )
                */
            ;
            $this->get('mailer')->send($message);

        return $this->redirectToRoute('homepage');
    }








}

