<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Image;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/album')]
class AdminAlbumController extends AbstractController
{
    #[Route('/', name: 'app_admin_album_index', methods: ['GET'])]
    public function index(AlbumRepository $albumRepository): Response
    {
        return $this->render('admin/album/index.html.twig', [
            'albums' => $albumRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_album_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($album);
            $entityManager->flush();
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $image = new Image();
                $image->setAlbum($album);
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                $image->setName($newFilename);
                $imageFile->move(
                    $this->getParameter('images_albums'),
                    $newFilename
                );
                $entityManager->persist($image);
                $entityManager->flush();
            }
            foreach ($album->getMusiques() as $musique) {
                $musique->setAlbum($album);
                $entityManager->persist($musique);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_admin_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/album/new.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_album_show', methods: ['GET'])]
    public function show(Album $album): Response
    {
        return $this->render('admin/album/show.html.twig', [
            'album' => $album,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_album_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Album $album, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/album/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_album_delete', methods: ['POST'])]
    public function delete(Request $request, Album $album, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->getPayload()->getString('_token'))) {
            foreach ($album->getMusiques() as $musique) {
                $entityManager->remove($musique);
                $entityManager->flush();
            }
            $entityManager->remove($album);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_album_index', [], Response::HTTP_SEE_OTHER);
    }
}
