<?php
namespace Application\Service;

use Doctrine\ORM\EntityManager;

class DoctrineService
{
    private ?EntityManager $em = null;

    public function getEntityManager(): EntityManager
    {
        if ($this->em === null) {
            // Carrega o EntityManager UMA VEZ
            // Ajuste o caminho se o seu bootstrap.php estiver em outro lugar
            $this->em = require __DIR__ . '/../bootstrap.php';
        }
        return $this->em;
    }
}