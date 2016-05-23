<?php
namespace Flower\CoreBundle\Entity\Impl;

use Flowcode\NotificationBundle\Senders\EmailSenderInterface;
use Flower\CoreBundle\Entity\NotificationChannel;
use Flower\CoreBundle\Entity\NotificationChannelHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 5/21/16
 * Time: 3:59 PM
 */
class EmailNotificationChannelHandler implements NotificationChannelHandler
{

    /**
     * @var EmailSenderInterface
     */
    private $sender;

    /**
     * @var Container
     */
    private $container;

    function handle(NotificationChannel $notificationChannel, array $params = array())
    {
        $toEmail = $notificationChannel->getParameter('toEmail')->getValue();
        $toName = $notificationChannel->getParameter('toName')->getValue();
        $fromEmail = $notificationChannel->getParameter('fromEmail')->getValue();
        $fromName = $notificationChannel->getParameter('fromName')->getValue();
        $subject = $notificationChannel->getName();
        $emailTemplate = $notificationChannel->getParameter('template')->getValue();

        $message = $this->container->get("templating")->render($emailTemplate, $params);

        $this->sender->send($toEmail, $toName, $fromEmail, $fromName, $subject, $message, true);
    }

    /**
     * @param EmailSenderInterface $sender
     */
    public function setSender(EmailSenderInterface $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = NULL)
    {
        $this->container = $container;
    }


}