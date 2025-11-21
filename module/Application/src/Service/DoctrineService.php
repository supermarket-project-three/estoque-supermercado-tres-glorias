<?php
namespace Application\Service;

use Doctrine\ORM\EntityManager;

class DoctrineService
{
    private ?EntityManager $em = null;

    public function getEntityManager(): EntityManager
    {
        if ($this->em === null) {
            // Carrega o EntityManager
            $this->em = require __DIR__ . '/../bootstrap.php';
        }
        return $this->em;
    }
}