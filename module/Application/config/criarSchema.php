<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use Laminas\ServiceManager\ServiceManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;

// Carrega configuração do Doctrine (dependências)
$config = require __DIR__ . '/../../../config/autoload/doctrine.global.php';

// Cria o ServiceManager
$container = new ServiceManager($config['dependencies']);

//Pega o EntityManager
/** @var EntityManager $entityManager */
$entityManager = $container->get(EntityManager::class);

// Pega todos os metadados das entidades
$metadados = $entityManager->getMetadataFactory()->getAllMetadata();

if (empty($metadados)) {
    echo "❌ Nenhuma entidade foi encontrada.\n";
    exit(1);
}

// Cria o SchemaTool
$schemaTool = new SchemaTool($entityManager);

// Atualiza ou cria o esquema do banco de dados
$schemaTool->updateSchema($metadados, true);

echo "✅ Banco de dados criado/atualizado com sucesso!\n";
