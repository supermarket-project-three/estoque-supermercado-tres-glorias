<?php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\DBAL\DriverManager;

$paths = [__DIR__ . '/../../module/Application/src/Entity'];
$isDevMode = true;

// Configuração do ORM
$config = new Configuration();

// Metadata via Attributes
$driver = new AttributeDriver($paths);
$config->setMetadataDriverImpl($driver);

// Naming strategy
$config->setNamingStrategy(new UnderscoreNamingStrategy());

// Proxy configuration
$proxyDir = __DIR__ . '/../../data/proxies';
if (!is_dir($proxyDir)) {
    mkdir($proxyDir, 0777, true);
}
$config->setProxyDir($proxyDir);
$config->setProxyNamespace('Proxies');
$config->setAutoGenerateProxyClasses($isDevMode);

// Conexão com o banco
$connectionParams = [
    'dbname'   => 'supermercado_tres_glorias',
    'user'     => 'root',
    'password' => '',
    'host'     => 'localhost',
    'driver'   => 'pdo_mysql',
    'charset'  => 'utf8',
];

// Registro da factory para Laminas ServiceManager
return [
    'dependencies' => [
        'factories' => [
            EntityManager::class => function ($container) use ($connectionParams, $config) {
                $conn = DriverManager::getConnection($connectionParams, $config);
                return new EntityManager($conn, $config); // ⚡ Construtor direto
            },
        ],
    ],
];
