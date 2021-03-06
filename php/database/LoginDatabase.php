<?php
    namespace Musicplayer\Database;

    class LoginDatabase
    {
        function __construct($pdo)
        {
            $this->pdo = $pdo;
        }

        function getData($statement)
        {
            $query = $this->pdo->prepare($statement);
            $query->execute();
            return $query->fetchAll();
        }
    }
?>