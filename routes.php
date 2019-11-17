<?php

/**
 * Realizando o agrupamento dos endpoints iniciados por api
 */
$app->group('/api', function()
{
    /**
     * Dentro de api, /user
     */
    $this->group('/user', function()
    {
        $this->post('', '\App\api\Controllers\UserController:createUser');
        /**
         * Validação se existe valor inteiro ao final da URL.
         */
        $this->get('/{id:[0-9]+}', '\App\api\Controllers\UserController:getUser');
        $this->delete('/{id:[0-9]+}', '\App\api\Controllers\UserController:deleteUser');
    });

    $this->group('/users', function()
    {
        $this->get('', '\App\api\Controllers\UserController:getUsers');
    });
});