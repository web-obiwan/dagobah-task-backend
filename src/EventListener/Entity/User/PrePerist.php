<?php

declare(strict_types=1);

namespace App\EventListener\Entity\User;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
readonly class PrePerist
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function prePersist(User $user): void
    {
        $plaintextPassword = $user->getPlainPassword();

        if ($plaintextPassword === null) {
            return;
        }

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
    }
}
