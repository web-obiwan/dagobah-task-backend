<?php

declare(strict_types=1);

namespace App\EventListener;

use Doctrine\ORM\Exception\ORMException;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    /**
     * @throws ORMException
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        // User
        $data['user'] = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ];

        $event->setData($data);
    }
}
