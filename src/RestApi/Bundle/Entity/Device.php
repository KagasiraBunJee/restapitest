<?php

namespace RestApi\Bundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Device
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $id;
    
    /**
     * @var string
     * @ORM\Column(name="platform", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $platform;

    /**
     * @var string
     * @ORM\Column(name="deviceId", type="string", length=255, unique=true, nullable=false)
     * @Assert\NotBlank()
     */
    private $deviceId;

    
    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="devices")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $user;

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
     * Set platform
     *
     * @param string $platform
     * @return Device
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Get platform
     *
     * @return string 
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set deviceId
     *
     * @param string $deviceId
     * @return Device
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    /**
     * Get deviceId
     *
     * @return string 
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * Set user
     *
     * @param \RestApi\Bundle\Entity\User $user
     * @return Device
     */
    public function setUser(\RestApi\Bundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \RestApi\Bundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
