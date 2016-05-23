<?php

namespace Flower\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * NotificationChannelParameter
 *
 * @ORM\Table(name="core_notification_channel_parameter")
 * @ORM\Entity(repositoryClass="\Flower\CoreBundle\Repository\NotificationChannelParameterRepository")
 */
class NotificationChannelParameter
{
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
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @ManyToOne(targetEntity="\Flower\CoreBundle\Entity\NotificationChannelImpl", inversedBy="parameters")
     * @JoinColumn(name="notification_channel_id", referencedColumnName="id")
     */
    private $notificationChannel;


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
     * @return NotificationChannelParameter
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
     * Set value
     *
     * @param string $value
     * @return NotificationChannelParameter
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getNotificationChannel()
    {
        return $this->notificationChannel;
    }

    /**
     * @param mixed $notificationChannel
     */
    public function setNotificationChannel($notificationChannel)
    {
        $this->notificationChannel = $notificationChannel;
    }


}
