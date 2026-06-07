<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

// Authentificateur personnalisé gérant deux formulaires de login distincts (admin et user)
// Extends AbstractLoginFormAuthenticator pour bénéficier du comportement de base des formulaires
class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    // Injection de dépendances : générateur d'URL Symfony et repository des utilisateurs
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {
    }

    // Détermine si cet authentificateur doit traiter la requête courante
    // Actif uniquement sur les requêtes POST vers /login/admin ou /login/user
    public function supports(Request $request): bool
    {
        return $request->isMethod('POST')
            && in_array($request->getPathInfo(), ['/login/admin', '/login/user'], true);
    }

    // Crée le Passport Symfony avec les credentials extraits du formulaire
    public function authenticate(Request $request): Passport
    {
        $email = trim((string) $request->request->get('email', ''));
        $password = (string) $request->request->get('password', '');
        $csrfToken = (string) $request->request->get('_csrf_token'); // token anti-CSRF
        $isAdminLogin = '/login/admin' === $request->getPathInfo();   // détecte le type de formulaire

        // Sauvegarde l'email en session pour pré-remplir le formulaire si erreur
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            // UserBadge : charge l'utilisateur depuis la base par son email
            new UserBadge($email, function (string $userIdentifier) use ($isAdminLogin) {
                $user = $this->userRepository->findOneBy(['email' => strtolower($userIdentifier)]);

                // Erreur si le compte n'existe pas
                if (!$user) {
                    throw new CustomUserMessageAuthenticationException('Compte introuvable.');
                }

                // Sur /login/admin, refuse les utilisateurs sans ROLE_ADMIN
                if ($isAdminLogin && !in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                    throw new CustomUserMessageAuthenticationException('Ce compte ne peut pas acceder a l espace administrateur.');
                }

                return $user;
            }),
            // PasswordCredentials : Symfony vérifie automatiquement le hash bcrypt/argon2
            new PasswordCredentials($password),
            // CsrfTokenBadge : vérifie le token CSRF du formulaire (action 'authenticate')
            [new CsrfTokenBadge('authenticate', $csrfToken)]
        );
    }

    // Appelée après une authentification réussie
    // Redirige vers le bon dashboard selon le rôle et le formulaire utilisé
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Admin se connectant via /login/admin → tableau de bord admin
        if (in_array('ROLE_ADMIN', $token->getRoleNames(), true) && '/login/admin' === $request->getPathInfo()) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin_dashboard'));
        }

        // Tous les autres cas → espace utilisateur
        return new RedirectResponse($this->urlGenerator->generate('app_user_dashboard'));
    }

    // URL de redirection si l'utilisateur tente d'accéder à une route protégée sans être connecté
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login_user');
    }
}