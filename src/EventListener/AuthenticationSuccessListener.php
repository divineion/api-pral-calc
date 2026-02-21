<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
// nom de la méthode à préciser ds l'attribut
#[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_success', method: 'onAuthenticationSuccess')]
class AuthenticationSuccessListener
{
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $event->setData([
            'token' => $data['token'],
            'user' => [
                'id'         => $user->getId(),
                'email'      => $user->getEmail(),
                'username'   => $user->getFullName(),
                'roles'      => $user->getRoles(),
                'isVerified' => $user->isVerified(),
            ],
        ]);
    }
}
