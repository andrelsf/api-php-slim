<?php

namespace App\api\Controllers;

use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\UserCredentials;
use OAuth2\Request;
use OAuth2\Server;

class OAuthController
{
    private $server;

    public function __construct($container)
    {
        $this->server = new Server($container->get('OAuth2'));
        // Add the "Client Credentials"
        $this->server->addGrantType(new ClientCredentials($container->get('OAuth2')));
        // Add the "User Credentials"
        $this->server->addGrantType(new UserCredentials($container->get('OAuth2')));
        
    }

    /**
     * generateToken
     * return Bearer token
     */
    public function generateToken()
    {
        $this->server->handleTokenRequest(Request::createFromGlobals())->send();
    }

    /**
     * validateToken
     * return json_enconde
     */
    public function validateToken()
    {
        // Handle a request to a resource and authenticate the access token
        if (!$this->server->verifyResourceRequest(Request::createFromGlobals())) {
            $this->server->getResponse()->send();
            return;
        }

        return json_encode(array('success' => true, 'message' => 'Aaila! You have a valid Oauth2.0 Token'));
    }
}