<?php

namespace App\Repository;

use App\Models\Entity\OAuthAccessToken;
use Doctrine\ORM\EntityManager;
use OAuth2\Storage\ClientCredentialsInterface;

class OauthAccessTokenRepository implements ClientCredentialsInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function getAccessToken($oauthToken)
    {
        $oauthAccessTokenRepository = $this->entityManager
                                           ->getRepository('App\Entity\OAuthAccessToken');
        $token = $oauthAccessTokenRepository->findOneBy(['token' => $oauthToken]);
        if ($token) {
            $token = $token->toArray();
            $token['expires'] = $token['expires']->getTimestamp();
        }

        return $token;
    }

    public function setAccessToken(
        $oauthToken, $clientIdentifier, $userEmail, $expires, $scope = null
    ) {
        $client = $this->entityManager
                        ->getRepository('App\Entity\OauthClient')
                        ->findOneBy(['client_identifier' => $clientIdentifier]);
        $user = $this->entityManager
                        ->getRepository('App\Entity\OauthUser')
                        ->findOneBy(['email' => $userEmail]);
        $token = OAuthAccessToken::fromArray(
            [
                'token'     => $oauthToken,
                'client'    => $client,
                'user'      => $user,
                'expires'   => (new \DateTime())->setTimestamp($expires),
                'scope'     => $scope,
            ]
        );

        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }
}