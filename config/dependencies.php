<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;


return [
    'doctrine' => [

        'dev_mode' => true,

        'cache_dir' => __DIR__ . '/../var/cache/doctrine/cache',

        'metadata_dirs' => [__DIR__ . '/../src/Model'],

        'connection' => [
            'driver' => 'pdo_mysql',
            'host' => getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'dbname' => getenv('DB_NAME'),
            'charset' => 'utf8mb4'
        ]
    ],
    EntityManager::class => function(ContainerInterface $container) {
        $settings = $container->get('doctrine');


        $cache = $settings['dev_mode'] ?
            new ArrayAdapter() :
            new FilesystemAdapter(directory: $settings['cache_dir']);

        $config = ORMSetup::createAttributeMetadataConfiguration(
            $settings['metadata_dirs'],
            $settings['dev_mode'],
            null,
            $cache
        );

        $config->setNamingStrategy(new UnderscoreNamingStrategy());

        $connection = DriverManager::getConnection($settings['connection'], $config);

        return new EntityManager($connection, $config);
    },
    Twig::class => function () {
        return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
    },

    'redis' => [
        'host' => getenv('DB_HOST'),
        'port' => 6379,
        'connectTimeout' => 2.5,
    ],

    Redis::class => function (ContainerInterface $container) {
        $settings = $container->get('redis');
        return new Redis($settings);
    }
];