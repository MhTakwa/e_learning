<?php

namespace App\Controller;

use App\Entity\Qcm;
use App\Form\QcmType;
use App\Repository\QcmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/qcm")
 */
class QcmController extends AbstractController
{
    /**
     * @Route("/", name="qcm_index", methods={"GET"})
     */
    public function index(QcmRepository $qcmRepository): Response
    {
        return $this->render('qcm/index.html.twig', [
            'qcms' => $qcmRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="qcm_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $qcm = new Qcm();
        $form = $this->createForm(QcmType::class, $qcm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($qcm);
            $entityManager->flush();

            return $this->redirectToRoute('qcm_index');
        }

        return $this->render('qcm/new.html.twig', [
            'qcm' => $qcm,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="qcm_show", methods={"GET"})
     */
    public function show(Qcm $qcm): Response
    {
        return $this->render('qcm/show.html.twig', [
            'qcm' => $qcm,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="qcm_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Qcm $qcm): Response
    {
        $form = $this->createForm(QcmType::class, $qcm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('qcm_index');
        }

        return $this->render('qcm/edit.html.twig', [
            'qcm' => $qcm,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="qcm_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Qcm $qcm): Response
    {
        if ($this->isCsrfTokenValid('delete'.$qcm->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($qcm);
            $entityManager->flush();
        }

        return $this->redirectToRoute('qcm_index');
    }
}
