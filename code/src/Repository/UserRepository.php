<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository
{
  private \PDO $conn;

  public function __construct(string $databaseUrl)
  {
    // the database url look like: 'pgsql:host=127.0.0.1;port=5432;dbname=axiom/axiom/axiom'
    // I have to split it on "/" to get the database name, username and password
    $credentials = explode('/', $databaseUrl);
    $database = $credentials[0];
    $username = $credentials[1];
    $password = $credentials[2];
    $this->conn = new \PDO($database, $username, $password);
  }


  public function findOneByEmail(string $email): ?User
  {
    $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);

    if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
      return new User(
        $row['id'], 
        $row['email'], 
        $row['password'],
        $row['first_name'],
        $row['last_name'],
        $row['roles']
      );
    }

    return null;
  }

  // findOneByUsername(), wich is the same as findOneByEmail(), so just wrap it
  public function findOneByUsername(string $username): ?User
  {
    return $this->findOneByEmail($username);
  }
}
