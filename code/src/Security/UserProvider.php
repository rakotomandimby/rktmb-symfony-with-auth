<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
  private UserRepository $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function loadUserByIdentifier(string $identifier): UserInterface
  {
    $user = $this->userRepository->findOneByEmail($identifier);

    if (!$user) {
      throw new UserNotFoundException();
    }

    return $user;
  }

  public function refreshUser(UserInterface $user): UserInterface
  {
    return $this->loadUserByIdentifier($user->getEmail());
  }

  public function supportsClass(string $class): bool
  {
    return $class === 'App\Entity\User';
  }
}
