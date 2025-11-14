<?php

require_once __DIR__ . '/../module/Application/src/bootstrap.php';

echo "Testando carregamento das entidades...\n\n";

try {
    $em = $entityManager;

    // Obtém todas as entidades detectadas pelo driver
    $allMetadata = $em->getMetadataFactory()->getAllMetadata();

    if (empty($allMetadata)) {
        echo "❌ Nenhuma entidade foi encontrada!\n";
        echo "Verifique:\n";
        echo " - Caminho correto das entidades no bootstrap.php\n";
        echo " - Se suas classes têm #[ORM\Entity]\n";
        echo " - Se o autoload do Composer está correto\n";
    } else {
        echo "✔ Entidades detectadas:\n\n";
        foreach ($allMetadata as $meta) {
            echo " - " . $meta->getName() . "\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Erro ao carregar entidades:\n" . $e->getMessage() . "\n";
}
