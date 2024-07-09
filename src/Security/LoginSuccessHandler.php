<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $httpUtils;
    private $authorizationChecker;

    public function __construct(HttpUtils $httpUtils, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->httpUtils = $httpUtils;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        // Vérifier si l'utilisateur est un administrateur
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            // Redirection vers le tableau de bord admin
            return $this->httpUtils->createRedirectResponse($request, 'app_admin_user_index');
        } else {
            // Redirection vers le tableau de bord utilisateur standard
            return $this->httpUtils->createRedirectResponse($request, 'app_album_index');
        }
    }
}
