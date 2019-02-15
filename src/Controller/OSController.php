<?php

namespace App\Controller;

use App\Entity\OS;
use App\Form\OS1Type;
use App\Repository\OSRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/o/s")
 */
class OSController extends AbstractController
{
    /**
     * @Route("/", name="o_s_index", methods={"GET"})
     */
    public function index(OSRepository $oSRepository): Response
    {
        return $this->render('os/index.html.twig', [
            'o_ss' => $oSRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="o_s_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $o = new OS();
        $form = $this->createForm(OS1Type::class, $o);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($o);
            $entityManager->flush();

            return $this->redirectToRoute('o_s_index');
        }

        return $this->render('os/new.html.twig', [
            'o' => $o,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="o_s_show", methods={"GET"})
     */
    public function show(OS $o): Response
    {
        return $this->render('os/show.html.twig', [
            'o' => $o,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="o_s_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OS $o): Response
    {
        $form = $this->createForm(OS1Type::class, $o);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('o_s_index', [
                'id' => $o->getId(),
            ]);
        }

        return $this->render('os/edit.html.twig', [
            'o' => $o,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="o_s_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OS $o): Response
    {
        if ($this->isCsrfTokenValid('delete'.$o->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($o);
            $entityManager->flush();
        }

        return $this->redirectToRoute('o_s_index');
    }
}
