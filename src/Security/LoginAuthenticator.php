<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Get the user object from the token
        $user = $token->getUser();

        // Check the user's role
        if ($user->getRoles() == 'ROLE_EMPLOYE') {
            // Redirect to app_mission for employees
            return new RedirectResponse($this->urlGenerator->generate('app_mission'));
        } elseif ($user->getRoles() == 'ROLE_CHEF_DE_PARC') {
            // Redirect to app_voiture for chefs de parc
            return new RedirectResponse($this->urlGenerator->generate('app_voiture'));
        } elseif ($user->getRoles() == 'ROLE_ADMIN') {
            // Redirect to app_voiture for chefs de parc
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        } elseif ($user->getRoles() == 'ROLE_SCGEN') {
            // Redirect to app_voiture for chefs de parc
            return new RedirectResponse($this->urlGenerator->generate('valid_miss'));
        } elseif ($user->getRoles() == 'ROLE_PERSONNEL') {
            // Redirect to app_voiture for chefs de parc
            return new RedirectResponse($this->urlGenerator->generate('ord-miss'));
        } elseif ($user->getRoles() == 'ROLE_FINANCIER') {
            // Redirect to app_voiture for chefs de parc
            return new RedirectResponse($this->urlGenerator->generate('indsf'));
        }
        return null;
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
