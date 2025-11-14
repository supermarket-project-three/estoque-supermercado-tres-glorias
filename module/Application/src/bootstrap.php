<?php

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

require_once __DIR__ . '/../../../vendor/autoload.php';

// Diretórios
$paths = [ __DIR__ . '/Entity' ];
$proxyDir = __DIR__ . '/Proxies';

// Criar se não existir
if (!is_dir($proxyDir)) {
    mkdir($proxyDir, 0777, true);
}

// Configuração ORM
$config = ORMSetup::createAttributeMetadataConfiguration(
    $paths,
    true,        // Dev mode
    $proxyDir,   // Diretório de proxies
);

// Conexão com banco
$connection = [
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'supermercado_tres_glorias',
    'host'     => 'localhost',
    'charset'  => 'utf8'
];

// Criar EntityManager
$entityManager = new EntityManager(
    DriverManager::getConnection($connection, $config),
    $config
);

return $entityManager;
