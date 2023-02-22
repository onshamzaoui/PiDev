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

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
//         $emailUser = $request->request->get('email_user', '');
// $password = $request->request->get('password', '');

// // log email and password
// $this->logger->info('Email: ' . $emailUser);
// $this->logger->info('Password: ' . $password);
        $emailUser = $request->request->get('email_user', '');


        $request->getSession()->set(Security::LAST_USERNAME, $emailUser);

        return new Passport(
            new UserBadge($emailUser),
            new PasswordCredentials($request->request->get('password_user', '')),
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

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        return new RedirectResponse("/login");
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
//     public function authenticate2(Request $request): TokenInterface
// {
//     $email = $request->request->get('email');
//     $password = $request->request->get('password');

//     $user = $this->userRepository->findOneBy(['email' => $email]);

//     if (!$user) {
//         throw new AuthenticationException('User not found');
//     }

//     if (!$this->passwordHasher->isPasswordValid($user, $password)) {
//         throw new AuthenticationException('Invalid password');
//     }

//     $token = new UsernamePasswordToken($user, $password, 'main', $user->getRoles());

//     return $token;
// }
}
