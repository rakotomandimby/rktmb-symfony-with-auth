<?php
namespace App\Repository;

class MySQL{
    private $pdo;
    private $es;
    public function __construct()
    {
        $this->pdo = new \PDO('mysql:host=mysal.rktmb.org;'
                              .'port=3306;'
                              .'dbname=rktmb;',
                              'rktmb',
                              'rktmb');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function close()
    {
        $this->pdo = null;
    }

  
    public function getOldestIncompleteCall(): array
    {
        $this->pdo->setAttribute( \PDO::ATTR_EMULATE_PREPARES, false );
        $stmt = $this->pdo->prepare(
            "SELECT uniqueid, did, start_time, caller_id FROM calls 
             WHERE start_time > '2021-06-03 19:39:50'
             AND end_time <> '0000-00-00 00:00:00' 
             AND 3cx_agent_name IS NULL 
             AND 3cx_record IS NULL 
             ORDER BY id ASC LIMIT 1"
        );
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }
}
