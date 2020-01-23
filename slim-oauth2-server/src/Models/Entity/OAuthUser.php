<?php

namespace App\Models\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity @Table(name="oauth_users")
 */
class OAuthUser extends EncryptableFieldEntity
{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @Column(type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     * @Column(type="string")
     */
    private $password;

    /**
     * @var \DateTime
     * @Column(type="datetime", nullable=false, name="create_at")
     */
    private $createAt;

    /**
     * @var \DateTime
     * @Column(type="datetime", nullable=true, name="update_at")
     */
    private $updateAt;

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
     * Get createAt
     * 
     * @return \DateTime
     */
    public function getCreateAt(): \DateTime
    {
        return $this->createAt;
    }

    /**
     * Set createAt
     * 
     * @return $this
     */
    public function setCreateAt()
    {
        $this->createAt = new \DateTime('now');
        return $this;
    }

    /**
     * Get updateAt
     * 
     * @return \DateTime
     * @return string if is null
     */
    public function getUpdateAt()
    {
        if ($this->updateAt) {
            return $this->updateAt;
        }

        return "";
    }

    /**
     * Set updateAt
     * 
     * @return $this
     */
    public function setUpdateAt()
    {
        $this->updateAt = new \DateTime('now');
        return $this;
    }

    /**
     * Set email
     * 
     * @param string $email
     * @return User
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     * 
     * @param string $password
     * @return User
     */
    public function setPassword(string $password)
    {
        $this->password = $this->encryptField($password);
        return $this;
    }

    /**
     * Get password
     * 
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Verify user's password
     * 
     * @param string $password
     * @return Boolean
     */
    public function verifyPassword(string $password): bool
    {
        return $this->verifyEncryptedFieldValue($this->getPassword(), $password);
    }

    /**
     * To Array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'user_id' => $this->id,
            'scope' => null,
        );
    }
}