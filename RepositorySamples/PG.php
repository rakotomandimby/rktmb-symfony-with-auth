<?php
namespace App\Tool;

class PG
{
    private $connString="pgsql:host=pgsql.rktmb.org;port=5432;dbname=rktmb;user=rktmb;password=rktmb";
    private $pdo;
    public function __construct()
    {
        $this->pdo = new \PDO($this->connString);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function writeIssue():array
    {
        $theQuery="INSERT INTO issues () VALUE ();";
        
        $stmt = $this->pdo->prepare($theQuery);
        return [];
    }
    private function getSyncedIssues():array
    {
        $theQuery="SELECT ";
        
        $stmt = $this->pdo->prepare($theQuery);
        $stmt->execute([
        ]);
        
        $result=array();
        if($stmt->rowCount() > 0){
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC))
            {
                $result[]=$row;
            }
        }
        return $result;
    }
    
}
