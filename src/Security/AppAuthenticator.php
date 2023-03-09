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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;


class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    private EmailVerifier $emailVerifier;
    use TargetPathTrait;
    
    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator,EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    public function authenticate(Request $request): Passport
    {
        $emailUser = $request->request->get('emailUser', '');
        // // $password = $request->request->get('password', '');
        // dump($emailUser);
        // dump($passwordUser);
        $request->getSession()->set(Security::LAST_USERNAME, $emailUser);

        return new Passport(
            new UserBadge($emailUser),
            new PasswordCredentials($request->request->get('passwordUser', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
        // dump($emailUser, $request->request->get('password_user', ''));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        $user = $token->getUser();
        // // dump($user);
        if($user->isIsVerified()==true) {
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        } else {
            return new RedirectResponse($this->urlGenerator->generate('default'));
        }
    }
                return new RedirectResponse("/login");

}
        //working code
        // $user = $token->getUser();
        // $roles = $user->getRoles();
        // $isClient = in_array(strtolower(trim("ROLE_CLIENT")), array_map('strtolower', $roles), true);
        
        // if ($isClient) {
        //     // Redirect to the client profile page
        //     return new RedirectResponse($this->urlGenerator->generate('app_profile'));
        // } else {
        //     // Debug code - remove this once the issue is resolved
        //     dump($user, $roles, $isClient);
        
        //     // Redirect to the default page
        //     return new RedirectResponse("/default");
        // }
        // if(in_array('ROLE_CLIENT',$user->getRoles(),true)) {
        //     return new RedirectResponse($this->urlGenerator->generate('/profile'));
        // }

        // // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        // return new RedirectResponse($this->urlGenerator->generate('display_client'));
        // $user = $token->getUser();
        // if($user->isIsVerified()==true) {
        //                 return new RedirectResponse($this->urlGenerator->generate('profile'));}

        //         if (in_array('ROLE_ADMIN', $user->getRoles())) {
        //             return new RedirectResponse($this->urlGenerator->generate('user'));
        //         } 
        //         else{
        //             if (in_array('ROLE_PRO', $user->getRoles())) {
        //                 return new RedirectResponse($this->urlGenerator->generate('profile'));
        //             } else{
        //             if (in_array('ROLE_CLIENT', $user->getRoles())) {
        //                 return new RedirectResponse($this->urlGenerator->generate('default'));
        //             }
        //         }
        //     }
        // }
            

    

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

}