<?php
// scripts/criarUsuarioMestre.php

/**
 * Script para criar o primeiro usuário administrador (Mestre) no banco de dados.
 */

// 1. Carrega o Doctrine
require_once __DIR__ . '/../bootstrap.php';

use Application\Entity\Usuario;

// 2. Define os dados do Admin
$emailMestre = "mestre@tresglorias.com";
$senhaMestre = "mestre123";
$nomeMestre = "Administrador Mestre";

echo "Iniciando criação do usuário mestre...\n";

try {
    // 3. Verifica se o usuário já existe (para não duplicar)
    $repo = $entityManager->getRepository(Usuario::class);
    $usuarioExistente = $repo->findOneBy(['email' => $emailMestre]);

    if ($usuarioExistente) {
        echo "AVISO: O usuário '$emailMestre' já existe no banco.\n";
        exit;
    }

    // 4. Cria a nova entidade
    $mestre = new Usuario();
    $mestre->setNome($nomeMestre);
    $mestre->setEmail($emailMestre);
    $mestre->setTipo('admin');
    
    // 5. USA O MÉTODO QUE GERA O HASH
    $mestre->setSenhaComHash($senhaMestre);

    // 6. Salva no banco de dados
    $entityManager->persist($mestre);
    $entityManager->flush();

    echo "--------------------------------------------------\n";
    echo "✅ Usuário Mestre criado com sucesso!\n";
    echo "   Email: $emailMestre\n";
    echo "   Senha: $senhaMestre\n";
    echo "--------------------------------------------------\n";

} catch (\Exception $e) {
    echo "❌ ERRO AO CRIAR USUÁRIO: " . $e->getMessage();
}