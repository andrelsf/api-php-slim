<?php

namespace App\Repository;

use Doctrine\ORM\EntityManager;
use OAuth2\Storage\ClientCredentialsInterface;

class OAuthUserRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Verify User Credentials
     * 
     * @param string $email $password
     * @return boolean
     */
    public function checkUserCredentials(string $email, string $password)
    {
        $userRepository = $this->entityManager
                                ->getRepository('App\Entity\OAuthUser');
        $user = $userRepository->findOneBy(['email' => $email]);
        if ($user) {
            return $user->verifyPasswor($password);
        }

        return false;
    }

    /**
     * @return
     * ARRAY the associated 'user_id' and optional 'scope' values
     * This function MUST return FALSE if the requested user does not exist or is invalid.
     * "scope" is a space-separated list of restricred scopes.
     * 
     * @code
     * return array(
     *  'user_id'   => USER_ID, // REQUIRED user_id to be stored with the authorization code or access toke
     *  'scope'     => SCOPE    // OPTIONAL space-separated list of restricted scopes
     * );
     * @endcode
     */
    public function getUserDetails(string $email)
    {
        $userRepository = $this->entityManager
                                ->getRepository('App\Entity\OAuthUser');
        $user = $userRepository->findOneBy(['email' => $email]);
        if ($user) {
            $user = $user->toArray();
        }

        return $user;
    }
}