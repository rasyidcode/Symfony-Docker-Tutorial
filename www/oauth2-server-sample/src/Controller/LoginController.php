<?php

namespace App\Controller;

use App\Entity\OAuth2ClientProfile;
use App\Entity\OAuth2UserConsent;
use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface             $entityManager
    )
    {
    }

    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/consent', name: 'app_consent', methods: ['GET', 'POST'])]
    public function consent(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse|Response
    {
        $clientId = $request->query->get('client_id');
        if (!$clientId || !ctype_alnum($clientId) || !$this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        $appClient = $this->entityManager->getRepository(Client::class)->findOneBy(['identifier' => $clientId]);
        if (!$appClient) {
            return $this->redirectToRoute('app_index');
        }

        $appProfile = $this->entityManager->getRepository(OAuth2ClientProfile::class)->findOneBy(['client' => $appClient]);
        $appName = $appProfile->getName();

        $requestedScopes = explode(' ', $request->query->get('scope'));
        $clientScopes = $appClient->getScopes();

        if (count(array_diff($requestedScopes, $clientScopes)) > 0) {
            return $this->redirectToRoute('app_index');
        }

        $user = $this->getUser();
        $userConsents = $user->getOAuth2UserConsents()->filter(
            fn(OAuth2UserConsent $consent) => $consent->getClient() === $appClient
        )->first() ?: null;
        $userScopes = $userConsents?->getScopes() ?? [];
        $hasExistingScopes = count($userScopes) > 0;

        if (count(array_diff($requestedScopes, $userScopes)) === 0) {
            $request->getSession()->set('consent_granted', true);
            return $this->redirectToRoute('oauth2_authorize', $request->query->all());
        }

        $requestedScopes = array_diff($requestedScopes, $userScopes);

        $scopeNames = [
            'profile' => 'Your Profile',
            'email' => 'Your email address',
            'blog_read' => 'Your blog posts (read)',
            'blog_write' => 'Your blog posts (write)'
        ];

        $requestedScopeNames = array_map(fn($scope) => $scopeNames[$scope], $requestedScopes);
        $existingScopes = array_map(fn($scope) => $scopeNames[$scope], $userScopes);

        if ($request->isMethod('POST')) {
            if ($request->request->get('consent') === 'yes') {
                $request->getSession()->set('consent_granted', true);

                $consents = $userConsents ?? new OAuth2UserConsent();
                $consents->setScopes(array_merge($requestedScopes, $userScopes));
                $consents->setClient($appClient);
                $consents->setCreated(new \DateTimeImmutable());
                $consents->setExpires(new \DateTimeImmutable('+30 days'));
                $consents->setIpAddress($request->getClientIp());

                $user->addOAuth2UserConsent($consents);

                $this->entityManager->persist($consents);
                $this->entityManager->flush();
            }

            if ($request->request->get('consent' === 'no')) {
                $request->getSession()->set('consent_granted', false);
            }

            return $this->redirectToRoute('oauth2_authorize', $request->query->all());
        }

        return $this->render('login/consent.html.twig', [
            'app_name' => $appName,
            'scopes' => $requestedScopes,
            'has_existing_scopes' => $hasExistingScopes,
            'existing_scopes' => $existingScopes
        ]);
    }
}
