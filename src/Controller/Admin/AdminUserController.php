<?php
namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user')]
class AdminUserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $error = 0;
            $userAlreadyExist = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if ($userAlreadyExist) {
                $error += 1;
                //Message d'erreur
                $this->addFlash(
                    'new-user-error',
                    'Cette mail existe déjà'
                );
            }

            $password =  $user->getPassword();
            $hasLength = strlen($password) >= 6;
            $hasNumber = preg_match('/[0-9]/', $password);
            $hasSpecialChar = preg_match('/[!@#$%^&*]/', $password);
            if (!$hasLength || !$hasNumber || !$hasSpecialChar) {
                $error += 1;
                $this->addFlash(
                    'new-user-error',
                    'Le mot de passe doit contenir au moins 6 caractères, un chiffre et un caractère spécial.'
                );
            }

            if ($error == 0) {
                //Met minusclule l'email
                $user->setEmail(strtolower($user->getEmail()));
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                );
                $user->setPassword($hashedPassword);

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $error = 0;
            $userAlreadyExist = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if ($userAlreadyExist) {
                $error += 1;
                //Message d'erreur
                $this->addFlash(
                    'edit-user-error',
                    'Cette mail existe déjà'
                );
            }

            $password =  $user->getPassword();
            $hasLength = strlen($password) >= 6;
            $hasNumber = preg_match('/[0-9]/', $password);
            $hasSpecialChar = preg_match('/[!@#$%^&*]/', $password);
            if (!$hasLength || !$hasNumber || !$hasSpecialChar) {
                $error += 1;
                $this->addFlash(
                    'edit-user-error',
                    'Le mot de passe doit contenir au moins 6 caractères, un chiffre et un caractère spécial.'
                );
            }

            if ($error == 0) {
                //Met minusclule l'email
                $user->setEmail(strtolower($user->getEmail()));
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                );
                $user->setPassword($hashedPassword);
                $entityManager->flush();
                return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}