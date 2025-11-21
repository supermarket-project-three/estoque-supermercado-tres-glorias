<?php

//Script básico para criar as tabelas do bd

// Carrega o bootstrap do Doctrine
require_once __DIR__ . '/../bootstrap.php';

use Doctrine\ORM\Tools\SchemaTool;

echo "Carregando metadados...\n";

try {
    $tool = new SchemaTool($entityManager);
    
    // Pega todas as classes que o Doctrine encontrou
    $classes = $entityManager->getMetadataFactory()->getAllMetadata();

    if (empty($classes)) {
        die("Nenhuma entidade encontrada! Verifique seu bootstrap.php e os namespaces.");
    }

    echo "Gerando schema para " . count($classes) . " entidades...\n";
    
    // Executa a criação das tabelas
    $tool->createSchema($classes);

    echo "✅ Tabelas criadas com sucesso no banco 'supermercado_tres_glorias'!";

} catch (\Exception $e) {
    echo "❌ Erro ao criar tabelas: " . $e->getMessage();
}