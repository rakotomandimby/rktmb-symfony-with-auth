<?php
// TestAuthController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestAuthController extends AbstractController
{
  #[Route('/api/test/auth', name: 'api_test_auth')]
  public function testAuth(): Response
  {
    /** @var App\Entory\User|null $user */
    $user = $this->getUser();
    
    if ($user) {
      return $this->json(
        [
          'id' => $user->getId(), 
          'username' => $user->getUserIdentifier(),
          'name' => $user->getFirstName() . ' ' . $user->getLastName(),
          'roles' => $user->getRoles()
        ]);
    }
    return $this->json(['message' => 'Not authenticated'], 401);
  }
}
