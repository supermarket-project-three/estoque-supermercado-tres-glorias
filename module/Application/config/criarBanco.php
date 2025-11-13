<?php

require_once __DIR__ . '/doctrine.php';

use Doctrine\ORM\Tools\SchemaTool;

// Obtém o EntityManager
$entityManager = require __DIR__ . '/doctrine.php';

// Obtém todos os metadados das entidades (classes com @Entity)
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
