<?php

namespace Flower\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;


/**
 * NotificationChannel
 *
 * @ORM\Table(name="core_notification_channel")
 * @ORM\Entity(repositoryClass="\Flower\CoreBundle\Repository\NotificationChannelRepository")
 */
class NotificationChannelImpl implements NotificationChannel
{

    const TYPE_WEB = 'web';
    const TYPE_EMAIL = 'email';
    const TYPE_SMS = 'sms';
    const TYPE_MOBILE = 'mobile';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100)
     */
    private $type;

    /**
     * @OneToMany(targetEntity="\Flower\CoreBundle\Entity\NotificationChannelParameter", mappedBy="notificationChannel")
     */
    private $parameters;

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
     * Set name
     *
     * @param string $name
     * @return NotificationChannel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return NotificationChannel
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    public function getParameter($parameterName)
    {
        foreach ($this->parameters as $parameter) {
            if ($parameterName == $parameter->getName()) {
                return $parameter;
            }
        }

        return null;
    }

    function __toString()
    {
        return $this->getName();
    }


}
