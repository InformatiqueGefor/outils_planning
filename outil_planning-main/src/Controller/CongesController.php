<?php

namespace App\Controller;

use App\Entity\Conges;
use App\Form\CongesType;
use App\Repository\CongesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/conges')]
class CongesController extends AbstractController
{
    #[Route('/', name: 'app_conges_index', methods: ['GET'])]
    public function index(CongesRepository $congesRepository): Response
    {
        return $this->render('conges/index.html.twig', [
            'conges' => $congesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_conges_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CongesRepository $congesRepository): Response
    {
        $conge = new Conges();
        $form = $this->createForm(CongesType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $congesRepository->save($conge, true);

            return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conges/new.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conges_show', methods: ['GET'])]
    public function show(Conges $conge): Response
    {
        return $this->render('conges/show.html.twig', [
            'conge' => $conge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conges_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conges $conge, CongesRepository $congesRepository): Response
    {
        $form = $this->createForm(CongesType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $congesRepository->save($conge, true);

            return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conges/edit.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conges_delete', methods: ['POST'])]
    public function delete(Request $request, Conges $conge, CongesRepository $congesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conge->getId(), $request->request->get('_token'))) {
            $congesRepository->remove($conge, true);
        }

        return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
    }
}
