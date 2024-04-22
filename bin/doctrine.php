#!/usr/bin/env php
<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;


$container = (require __DIR__ . '/../config/container.php');

ConsoleRunner::run(
    new SingleManagerProvider($container->get(EntityManager::class))
);