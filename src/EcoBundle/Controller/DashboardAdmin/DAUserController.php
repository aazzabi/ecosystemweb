<?php

namespace EcoBundle\Controller\DashboardAdmin;

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
class DAUserController extends Controller
{
    /**
     *
     * @Route("/user", name="da_users_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $users = array();
        $reparateurs = array();
        $reparateursPro = array();
        $livreurs = array();
        $respsSoc = array();
        $respsAsso = array();

        $allUsers = $this->getDoctrine()->getManager()->getRepository('EcoBundle:User')->findAll();

        foreach ($allUsers as $user) {
            if ($user->hasRole("ROLE_USER")) {
                $users[] = $user;
            }
            if ($user->hasRole("ROLE_REPARATEUR")) {
                $reparateurs[] = $user;
            }
            if ($user->hasRole("ROLE_REPARATEUR_PRO")) {
                $reparateursPro[] = $user;
            }
            if ($user->hasRole("ROLE_LIVREUR")) {
                $livreurs[] = $user;
            }
            if ($user->hasRole("ROLE_REPRESENTANT_ASSOC")) {
                $respsAsso[] = $user;
            }
            if ($user->hasRole("ROLE_REPRESENTANT_SOCIETE")) {
                $respsSoc[] = $user;
            }
        }

        return $this->render('@Eco/DashboardAdmin/User/index.html.twig', array(
            'users' => $users,
            'reparateurs' => $reparateurs,
            'reparateursPro' => $reparateursPro,
            'livreurs' => $livreurs,
            'respsSoc' => $respsSoc,
            'respsAsso' => $respsAsso,
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/user/new", name="da_users_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        $reparateur = new Reparateur();
        $user = new User();
        $livreur = new Livreur();
        $respSoc = new RespSoc();
        $respAsso = new RespAsso();

        $formUser = $this->createForm('EcoBundle\Form\UserType', $user);
        $formRep = $this->createForm('EcoBundle\Form\ReparateurType', $reparateur);
        $formLiv = $this->createForm('EcoBundle\Form\LivreurType', $livreur);
        $formRespSoc = $this->createForm('EcoBundle\Form\RespSocType', $respSoc);
        $formRespAsso = $this->createForm('EcoBundle\Form\RespAssoType', $respAsso);

        $formUser->handleRequest($request);
        $formRep->handleRequest($request);
        $formLiv->handleRequest($request);
        $formRespSoc->handleRequest($request);
        $formRespAsso->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('da_users_index');
        }

        if ($formRep->isSubmitted() && $formRep->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reparateur);
            $em->flush();
            return $this->redirectToRoute('da_users_index');
        }

        if ($formLiv->isSubmitted() && $formLiv->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($livreur);
            $em->flush();
            return $this->redirectToRoute('da_users_index');
        }

        if ($formRespSoc->isSubmitted() && $formRespSoc->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($respSoc);
            $em->flush();
            return $this->redirectToRoute('da_users_index');
        }

        if ($formRespAsso->isSubmitted() && $formRespAsso->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($respAsso);
            $em->flush();
            return $this->redirectToRoute('da_users_index');
        }

        return $this->render('@Eco/DashboardAdmin/User/new.html.twig', array(
            'user' => $user,
            'formUser' => $formUser->createView(),
            'formRep' => $formRep->createView(),
            'formLiv' => $formLiv->createView(),
            'formRespAsso' => $formRespAsso->createView(),
            'formRespSoc' => $formRespSoc->createView(),
        ));
    }
    /**
     * Finds and displays a user entity.
     *
     * @Route("/user/{id}", name="da_users_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }

        return $this->render('@Eco/DashboardAdmin/User/show.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/user/{id}/edit", name="da_users_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException("Vous n'êtes pas autorisés à accéder à cette page!", Response::HTTP_FORBIDDEN);
        }
        $editForm = $this->createForm('EcoBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('da_users_edit', array('id' => $user->getId()));
        }

        return $this->render('@Eco/DashboardAdmin/User/edit.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
        ));
    }
    /**
     * @Route("/user/delete/{id}", name="da_users_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteUserAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('EcoBundle:User')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('da_users_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('da_users_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}