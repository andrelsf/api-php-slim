<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use OAuth2\Storage\ClientCredentialsInterface;

class OAuthClientRepository extends EntityRepository implements ClientCredentialsInterface
{
    // /**
    //  * @var EntityManager
    //  */
    // private $entityManager;

    // public function __construct(EntityManager $entityManager)
    // {
    //     $this->entityManager = $entityManager;
    //     parent::__construct();
    // }
    
    public function getClientDetails($clientIdentifier)
    {
        $clientRepository = $this->entityManager
                                ->getRepository('App\Entity\OAuthClient');
        $client = $clientRepository->findOneBy(['client_identifier' => $clientIdentifier]);
        if ($client) {
            $client = $client->toArray();
        }
        return $client;
    }

    public function checkClientCredentials($clientIdentifier, $clientSecret = null)
    {
        $clientRepository = $this->entityManager
                                ->getRepository('App\Entity\OAuthClient');
        $client = $clientRepository->findOneBy(['client_identifier' => $clientIdentifier]);
        if ($client) {
            return $client->verifyClientSecret($clientSecret);
        }

        return false;
    }

    public function checkRestrictedGrantType($clientId, $grantType)
    {
        return true;
    }

    public function isPublicClient($clientId)
    {
        return false;
    }

    public function getClientScope($clientId)
    {
        return null;
    }
}