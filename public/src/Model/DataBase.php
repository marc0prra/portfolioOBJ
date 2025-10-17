<?php

namespace Perei\PortfolioObj\Model;


class Database
{
    public \PDO $connexion;

    public function __construct(\PDO $connexion)
    {
        $this->connexion = $connexion;
    }
    public function getConnexion(): \PDO
    {
        return $this->connexion;
    }
}