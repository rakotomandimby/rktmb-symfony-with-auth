<?php
namespace App\Entity;


use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  private ?int $id;
  private ?string $email;
  private ?string $password;
  // first name
  private ?string $firstName;
  // last name
  private ?string $lastName;
  // roles
  private ?string $roles;

  public function __construct(
    ?int $id = null, 
    ?string $email = null, 
    ?string $password = null,
    ?string $firstName = null,
    ?string $lastName = null,
    ?string $roles = null
  )
  {
    $this->id = $id;
    $this->email = $email;
    $this->password = $password;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->roles = $roles; 
  }

  public function getRoles(): array
  {
    // Roles are coma separated in the database
    // we need to explode them
    return explode(',', $this->roles);
  }

  public function setRoles(?array $roles): self
  {
    $this->roles = implode(',', $roles);
    return $this;
  }

  public function eraseCredentials()
  {
  }

public function getUserIdentifier(): string
  {
    return $this->email;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(?string $email): self
  {
    $this->email = $email;
    return $this;
  }
  
  public function getUsername(): ?string
  {
    return $this->email;
  }

  public function setUsername(?string $email): self
  {
    $this->email = $email;
    return $this;
  }

  public function getPassword(): ?string
  {
    return $this->password;
  }

  public function setPassword(?string $password): self
  {
    $this->password = $password;
    return $this;
  }

  public function getFirstName(): ?string
  {
    return $this->firstName;
  }

  public function setFirstName(?string $firstName): self
  {
    $this->firstName = $firstName;
    return $this;
  }

  public function getLastName(): ?string
  {
    return $this->lastName;
  }

  public function setLastName(?string $lastName): self
  {
    $this->lastName = $lastName;
    return $this;
  }
}
