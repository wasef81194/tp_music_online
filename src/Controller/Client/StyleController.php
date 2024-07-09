<?php

namespace App\Controller\Client;

use App\Entity\Style;
use App\Form\StyleType;
use App\Repository\StyleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/style')]
class StyleController extends AbstractController
{
    #[Route('/', name: 'app_style_index', methods: ['GET'])]
    public function index(StyleRepository $styleRepository): Response
    {
        return $this->render('style/index.html.twig', [
            'styles' => $styleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_style_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $style = new Style();
        $form = $this->createForm(StyleType::class, $style);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($style);
            $entityManager->flush();

            return $this->redirectToRoute('app_style_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('style/new.html.twig', [
            'style' => $style,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_style_show', methods: ['GET'])]
    public function show(Style $style): Response
    {
        return $this->render('style/show.html.twig', [
            'style' => $style,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_style_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Style $style, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StyleType::class, $style);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_style_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('style/edit.html.twig', [
            'style' => $style,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_style_delete', methods: ['POST'])]
    public function delete(Request $request, Style $style, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$style->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($style);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_style_index', [], Response::HTTP_SEE_OTHER);
    }
}
