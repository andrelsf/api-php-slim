<?php

namespace App\Models\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @Entity @Table(name="oauth_access_tokens")
 */
class OAuthAccessToken
{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @Column(type="string", length=40, unique=true)
     */
    private $token;

    /**
     * @var int
     * @Column(type="integer")
     */
    private $client_id;

    /**
     * @var int
     * @Column(type="integer", nullable=true)
     */
    private $user_id;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     */
    private $expires;

    /**
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    private $scope;

    /**
     * @var App\Entity\OAuthClient
     * @ManyToOne(targetEntity="OAuthClient", inversedBy="oauth_clients")
     * @JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var App\Entity\OAuthUser
     * @ManyToOne(targetEntity="OAuthUser", inversedBy="oauth_users")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Get id
     * 
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set token
     * 
     * @param string $token
     * @return App\Entity\OAuthAccessToken
     */
    public function setToken(string $token)
    {
        $this->toke = $token;
        return $this;
    }

    /**
     * Get token
     * 
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set client_id
     * 
     * @param string $clientId
     * @return OAuthAccessToken
     */
    public function setClientId(string $clientId)
    {
        $this->client_id = $clientId;
        return $this;
    }

    /**
     * Get client_id
     * 
     * @return string
     */
    public function getClientId(): string
    {
        return $this->client_id;
    }

    /**
     * Set user_id
     * 
     * @param string $userIdentifier
     * @return OAuthAccessToken
     */
    public function setUserId(string $userId)
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * Get user_identifier
     * 
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * Set expires
     * 
     * @param \DateTime $expires
     * @return OAuthAccessToken
     */
    public function setExpires(\DateTime $expires)
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * Get expires
     * 
     * @return \DateTime
     */
    public function getExpires(): \DateTime
    {
        return $this->expires;
    }

    /**
     * Set scope
     * 
     * @param string $scope
     * @return OAuthAccessToken
     */
    public function setScope(string $scope)
    {
        $this->scope = $scope;
    }

    /**
     * Get scope
     * 
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * Set client
     * 
     * @param App\Entity\OAuthClient $client
     * @return OAuthAccessToken
     */
    public function setClient(OAuthClient $client = null)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get client
     * 
     * @return App\Entity\OAuthClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set user
     * 
     * @param App\Entity\OAuthUser $user
     * @return OAuthRefreshToken 
     */
    public function setUser(OAuthUser $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     * 
     * @return App\Entity\OAuthUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param array $params
     */
    public static function fromArray(array $params)
    {
        $token = new self();
        foreach ($params as $property => $value) {
            $token->$property = $value;
        }
        return $token;
    }

    /**
     * To array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'token' => $this->token,
            'client_id' => $this->client_id,
            'user_id' => $this->user_id,
            'expires' => $this->expires,
            'scope' => $this->scope,
        );
    }
}