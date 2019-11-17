<?php

namespace App\api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;
use App\Models\UserRepository;
use Doctrine\ORM\EntityManager;

/**
 * Controller API de Usuarios
 */
class UserController
{
    /**
     * container class
     */
    private $container;

    /**
     * @method construct
     * @param [object] $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * GET: /api/users
     * 
     * @CURL:
     * curl -X GET localhost/api/users
     * 
     * @method getUsers Lista de todos os usuários
     * @param   [Request]   $request
     * @param   [Response]  $response
     * @param   [type]      $args
     * @return  [Response]  $response
     */
    public function getUsers($request, $response, $args)
    {
        $entityManager = $this->container->get(EntityManager::class);
        $userRepository = new UserRepository($entityManager);
        $usersList = $userRepository->getUsers();
        return $response->withJson(
            [
                'status' => 'success',
                'users' => $usersList
            ], 200, JSON_PRETTY_PRINT
        )->withHeader('Content-Type', 'application/json;charset=utf-8');
    }

    /**
     * POST: /api/user 
     * 
     * curl -X POST -H "Content-type: application/json" localhost/api/user \
     *  -d "{\"fullname\":\"Andre Ferreira\",\"email\":\"andre71luiz@gmail.com.br\",\"password\":\"andre12345\",\"isactive\":\"true\"}"
     * 
     * @method createUser function registra um novo usuário
     *
     * @param Request $request
     * @param Response $response
     * @param [type] $args
     * @return void
     */
    public function createUser($request, $response, $args)
    {
        $logger = $this->container->get('logger');
        $message = [];
        $post = (object) $request->getParams();
        $data = [
            'fullname'  => $post->fullname,
            'email'     => $post->email,
            'password'  => $post->password,
            'isactive'  => (bool) $post->isactive
        ];

        $this->validator->setValues($data);

        $this->validator->validate(
            $request,
            [
                'fullname' => v::stringType()->alpha()->notEmpty(),
                'email' => v::stringType()->noWhitespace()->notEmpty(),
                'password' => v::stringType()->alnum()->noWhitespace()->notEmpty(),
                'isactive' => v::stringType()->notEmpty(),
            ]
        );
        
        if ( !$this->validator->isValid() ){
            $message['message'] = $this->validator->getErrors();
            $message['code'] = 400;        
        } else {
            $entityManager = $this->container->get(EntityManager::class);
            $userRespository = new UserRepository($entityManager);
            
            $userExists = $userRespository->findByEmail($post->email);
            
            if ( !$userExists ) {
                $result = $userRespository->addUser(
                        $post->fullname,
                        $post->email,
                        $post->password,
                        (bool) $post->isactive
                );
                if ($result) {
                    $message['message'] = 'User add with successfully';
                    $message['code'] = 201;
                    $logger->info("POST: {$message['message']}");
                }
            } else {
                $message['message'] = 'email already exists';
                $message['code'] = 404;

                $logger->info("POST: {$message['message']}");
            }
        }
        return $response->withJson($message, (int) $message['code'], JSON_PRETTY_PRINT)
                        ->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET: curl -X GET localhost/api/user/1
     * 
     * @method getUser function Retorna detalhes de um usuário
     *
     * @param Request $request
     * @param Response $response
     * @param [type] $args
     * @return void
     */
    public function getUser($request, $response, $args)
    {
        $logger = $this->container->get('logger');

        $id = (int) $args['id'];

        $entityManager = $this->container->get(EntityManager::class);
        $userRespository = new UserRepository($entityManager);
        $user = $userRespository->getOneUser($id);
        
        $message['message'] = $user['message'];
        $message['status'] = $user['code'];

        $logger->info("GET User {$id}");
        return $response->withJson($message, (int) $message['status'], JSON_PRETTY_PRINT)
                    ->withHeader('Content-Type', 'application/json');
    }

    /**
     * DELETE: curl -X DELETE localhost/api/user/1
     * 
     * @method deleteUser function Remove um unico usuario
     *
     * @param Request $request
     * @param Response $response
     * @param [type] $args
     * @return void
     */
    public function deleteUser($request, $response, $args)
    {
        $logger = $this->container->get('logger');

        $id = (int) $args['id'];
    
        $entityManager = $this->container->get(EntityManager::class);
        $userRespository = new UserRepository($entityManager);
    
        $message = [];

        $user = $userRespository->deleteOneUser($id);
        $message['message'] = $user['message'];
        $message['status'] = $user['code'];
        
        $logger->info("DELETE User: {$id}");
        return $response->withJson($message, (int) $message['status'], JSON_PRETTY_PRINT)
                    ->withHeader('Content-Type', 'application/json');
    }
}