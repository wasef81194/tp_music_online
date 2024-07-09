<?php

namespace App\Controller\Client;

use App\Entity\Interprete;
use App\Form\InterpreteType;
use App\Repository\InterpreteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/interprete')]
class InterpreteController extends AbstractController
{
    #[Route('/', name: 'app_interprete_index', methods: ['GET'])]
    public function index(InterpreteRepository $interpreteRepository): Response
    {
        return $this->render('interprete/index.html.twig', [
            'interpretes' => $interpreteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_interprete_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $interprete = new Interprete();
        $form = $this->createForm(InterpreteType::class, $interprete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($interprete);
            $entityManager->flush();

            return $this->redirectToRoute('app_interprete_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interprete/new.html.twig', [
            'interprete' => $interprete,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_interprete_show', methods: ['GET'])]
    public function show(Interprete $interprete): Response
    {
        return $this->render('interprete/show.html.twig', [
            'interprete' => $interprete,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_interprete_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Interprete $interprete, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InterpreteType::class, $interprete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_interprete_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interprete/edit.html.twig', [
            'interprete' => $interprete,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_interprete_delete', methods: ['POST'])]
    public function delete(Request $request, Interprete $interprete, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$interprete->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($interprete);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_interprete_index', [], Response::HTTP_SEE_OTHER);
    }
}
