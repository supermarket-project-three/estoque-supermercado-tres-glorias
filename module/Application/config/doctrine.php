<?php 

    require_once __DIR__ . '/../../vendor/autoload.php';

    use Doctrine\ORM\EntityManager;
    use Doctrine\ORM\ORMSetup;
    use Doctrine\DBAL\DriverManager;

    $caminhoEntidade = [__DIR__ . '/../Entity'];
    $caminhoProxies = __DIR__ . '/../Proxies';

    $config = ORMSetup::createAttributeMetadataConfiguration($caminhoEntidade, true, $caminhoProxies);

    $parametrosConexao = [

        'dbname' => 'supermercado_tres_glorias',
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'driver' => 'pdo_mysql',
        'charset' => 'utf8'
    ];

    $conexao = DriverManager::getConnection($parametrosConexao, $config);
    $entityManager = new EntityManager($conexao, $config);

    return $entityManager;
?>