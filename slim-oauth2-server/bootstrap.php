<?php

/**
 * BOOTSTRAP: Responsabilidade de configurações
 * Concentra todas as definições feitas no autoload e configurações
 * de dependências da API.
 */
 
require __DIR__.'/vendor/autoload.php';

use Slim\App;
use Slim\Container;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use App\Models\UserRepository;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Awurth\SlimValidation\Validator;
use OAuth2\Autoloader;
use OAuth2\Storage\Pdo;

/**
 * Container Resources adiciona as definições
 */
$container = new Container(require __DIR__."/settings.php");

/**
 * Validator as a service
 */
$container['validator'] = function () {
    return new Validator();
};

/**
 * Handler logs with Monolog em arquivo
 */
$container['logger'] = function($container)
{
    $logger = new Logger($container['settings']['logger']['name']);
    $logFile = $container['settings']['logger']['logfile'];
    
    $stream = new StreamHandler($logFile, Logger::DEBUG);
    $fingersCrossed = new FingersCrossedHandler($stream, Logger::INFO);
    $logger->pushHandler($fingersCrossed);

    return $logger;
};

/**
 * Erros 404 - Not Fount
 * Handler Exceptions with errors 404
 */
$container['notFoundHandler'] = function ($container) 
{
    return function ($request, $response) use ($container) {
        return $container['response']->withStatus(404)
                             ->withHeader('Content-Type', 'application/json')
                             ->withJson(
                                 ['message' => 'Page not found']
                             );
    };
};

/**
 * Error Handling returns status code 405 Method Not Allowed
 */
$container['notAllowedHandler'] = function ($container) 
{
    return function ($request, $response, $methods) use ($container) {
        return $container['response']->withStatus(405)
                             ->withHeader('Allow', implode(', ', $methods))
                             ->withHeader('Content-Type', 'application/json')
                             ->withHeader('Access-control-Allow-Methods', implode(', ', $methods))
                             ->withJson([
                                 'status' => 405,
                                 'message' => "Method not allowed; Method must be one of: ".implode(', ', $methods)
                             ], 405);
    };
};

/**
 * Handler de exceções
 * Retorna as exceções e codigos de status via JSON
 */
$container['errorHandler'] = function ($container) 
{
    return function ($request, $response, $exception) use ($container) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        
        return $container['response']->withStatus($statusCode)
                             ->withHeader('Content-Type', 'application/json')
                             ->withJson(
                                    ['error' => $exception->getMessage()], 
                                    $statusCode,
                                    JSON_PRETTY_PRINT
                                );
    };
};

/**
 * Diretório de entidades e metadata do doctrine.
 */
$container[EntityManager::class] = function (Container $container): EntityManager 
{
    $config = Setup::createAnnotationMetadataConfiguration(
        $container['settings']['doctrine']['metadata_dirs'],
        $container['settings']['doctrine']['dev_mode']
    );

    $config->setMetadataDriverImpl(
        /** @var AnnotationDriver */
        new AnnotationDriver(
            /** @var AnnotationReader */
            new AnnotationReader,
            $container['settings']['doctrine']['metadata_dirs']
        )
    );

    $config->setMetadataCacheImpl(
        new FilesystemCache(
            $container['settings']['doctrine']['cache_dir']
        )
    );

    return EntityManager::create(
        $container['settings']['doctrine']['connection'],
        $config
    );
};

/**
 * Repositories
 */
$container[UserRepository::class] = function ($container) 
{
    return new UserRepository($container[EntityManager::class]);
};

/**
 * Implements Oauth2
 */
$container['OAuth2'] = function ($container) 
{
    $database = $container['settings']['doctrine']['connection'];

    Autoloader::register();

    $storage = new Pdo(
        array(
            'dsn' => "mysql:dbname=" . 
                    $database['dbname'] . 
                    ";host=" . $database['host'] . 
                    ";port=" . $database['port'],
            'username' => $database['user'],
            'password' => $database['password']
        )
    );

    return $storage;
};

/**
 * Instancia GLOBAL da APP (Singleton)
 * Gerenciamento de toda aplicação através de um ponto de acesso global
 * realizando a injeção de dependências dentro de um container.
 */
$app = new App($container);