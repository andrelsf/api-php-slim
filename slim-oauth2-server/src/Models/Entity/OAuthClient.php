<?php

namespace App\Models\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use OAuth2\Storage\ClientCredentialsInterface;

/**
 * @Entity @Table(name="oauth_clients")
 */
class OAuthClient extends EncryptableFieldEntity
{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @Column(type="string", nullable=false, unique=true, length=50)
     */
    private $client_identifier;

    /**
     * @var string
     * @Column(type="string", nullable=false, length=20)
     */
    private $client_secret;

    /**
     * @var string
     * @Column(type="string", length=255)
     */
    private $redirect_uri = '';

    /**
     * Get id
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set client_identifier
     * 
     * @param string $clientIdentifier
     * @return OAuthClient
     */
    public function setClientIdentifier($clientIdentifier): OAuthClient
    {
        $this->client_identifier = $clientIdentifier;
        return $this;
    }

    /**
     * Get client_identifier
     * 
     * @return string
     */
    public function getClientIdentifier(): string
    {
        return $this->client_identifier;
    }

    /**
     * Set client_secret
     * 
     * @param string $clientSecret
     * @return OAuthClient
     */
    public function setClientSecret(string $clientSecret): OAuthClient
    {
        $this->client_secret = $this->encryptField($clientSecret);
        return $this;
    }

    /**
     * Get client_secret
     * 
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->client_secret;
    }

    /**
     * Verify client's secret
     * 
     * @param string $password
     * @return Boolean
     */
    public function verifyClientSecret($clientSecret): bool
    {
        return $this->verifyEncryptedFieldValue($this->getClientSecret(), $clientSecret);
    }

    /**
     * Set redirect_uri
     * 
     * @param string $redirectUri
     * @return OAuthClient
     */
    public function setRedirectUri($redirectUri): OAuthClient
    {
        $this->redirect_uri = $redirectUri;
        return $this;
    }

    /**
     * Get redirect_uri
     * 
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirect_uri;
    }

    /**
     * Get info
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'client_id' => $this->client_identifier,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
        );
    }
}