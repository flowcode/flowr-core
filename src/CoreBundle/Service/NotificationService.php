<?php

namespace Flower\CoreBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flower\ModelBundle\Entity\Planner\Event;
use Flower\ModelBundle\Entity\Board\Task;
use Flower\ModelBundle\Entity\Board\History;
use Flower\ModelBundle\Entity\EmailNotification;
use Flower\ModelBundle\Entity\User\UserNotification;
use Flower\ModelBundle\Entity\Marketing\ContactList;
use Flower\ModelBundle\Entity\Marketing\CampaignMail;

/**
 * Description of NotificationService
 *
 * @author Francisco Memoli <fmemoli@flowcode.com.ar>
 */
class NotificationService implements ContainerAwareInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->em = $this->container->get("doctrine.orm.entity_manager");
    }

    public function notificateTaskChanged(Task $task, History $taskHistory)
    {
        $em = $this->em;
        $user = $task->getAssignee();
        if ($user != null) {
            //$opportunity = $em->getRepository('FlowerModelBundle:Clients\Opportunity')->findByBoard($task->getBoard());
            //$project = $em->getRepository('FlowerModelBundle:Project\Project')->findByBoard($task->getBoard());
            $message = $this->container->get("templating")->
            render('FlowerBoardBundle:Email:Task/statusChange.html.twig',
                array('task' => $task,
                    'history' => $taskHistory,
                    'account' => null,
                    'opportunity' => null,
                    'project' => null));
            $notification = new EmailNotification();
            $notification->setSubject("Task " . $task->getName() . " has changed ");
            $notification->setBody($message);
            $notification->setFromEmail($this->container->getParameter("default_mail_from"));
            $notification->setFromName($this->container->getParameter("default_mail_from_name"));
            $notification->setIsHTML(true);
            $notification->setStatus(EmailNotification::$STATUS_PENDING);
            $notification->setToEmail($user->getEmail());
            $notification->setToName($user->getLastname() . " " . $user->getFirstname());
            $em->persist($notification);

            if ($task->getCreator()->getId() != $user->getId()) {
                $userNotif = new UserNotification();
                $userNotif->setUser($user);
                $userNotif->setType(UserNotification::type_task);
                $userNotif->setUrl($this->container->get("router")->generate('task_show', array('id' => $task->getId())));
                $userNotif->setMessage("Se le ha asignado una tarea.");
                $em->persist($userNotif);
                $em->flush();

                /* real time notifications */
                $pusher = $this->container->get('lopi_pusher.pusher');
                $pusher->trigger('notifications-user-' . $user->getId(), 'new-notifications', 'updates');
            }

            $em->flush();
        }
    }

    public function getNotificateReminder($reminder)
    {
        $notification = new EmailNotification();
        $event = $reminder->getEvent();
        $user = $reminder->getUser();
        $notification->setToEmail($user->getEmail());
        $notification->setToName($user->getFirstname() . " " . $user->getLastname());
        $notification->setFromEmail($user->getEmail());
        $notification->setFromName($user->getHappyName());
        $notification->setSubject("Reminder event " . $event->getTitle());
        $meesage = $this->container->get("templating")->render('FlowerPlannerBundle:Email:Event/reminder.html.twig', array('event' => $event, "reminder" => $reminder));
        $notification->setBody($meesage);
        $notification->setIsHTML(true);
        $notification->setStatus(EmailNotification::$STATUS_PENDING);
        return $notification;
    }

    public function notificateNewEvent(Event $event)
    {
        $em = $this->em;
        $notification = new EmailNotification();
        $notification->setSubject("You are invited to " . $event->getTitle());
        $message = $this->container->get("templating")->render('FlowerPlannerBundle:Email:Event/new.html.twig', array('event' => $event));
        $notification->setBody($message);
        $notification->setFromEmail($this->container->getParameter("default_mail_from"));
        $notification->setFromName($this->container->getParameter("default_mail_from_name"));
        $notification->setIsHTML(true);
        $notification->setStatus(EmailNotification::$STATUS_PENDING);
        foreach ($event->getContacts() as $contact) {
            $newNotification = clone $notification;
            $newNotification->setToEmail($contact->getEmail());
            $newNotification->setToName($contact->getLastname() . " " . $contact->getFirstname());
            $em->persist($newNotification);
        }
        if ($event->getUsers()) {
            foreach ($event->getUsers() as $user) {
                $newNotification = clone $notification;
                $newNotification->setToEmail($user->getEmail());
                $newNotification->setToName($user->getLastname() . " " . $user->getFirstname());
                $em->persist($newNotification);

                if ($event->getOwner()->getId() != $user->getId()) {
                    $userNotif = new UserNotification();
                    $userNotif->setUser($user);
                    $userNotif->setType(UserNotification::type_event);
                    $userNotif->setUrl($this->container->get("router")->generate('event'));
                    $userNotif->setMessage("Fuiste invitado a un nuevo evento.");
                    $em->persist($userNotif);
                    $em->flush();

                    /* real time notifications */
                    $pusher = $this->container->get('lopi_pusher.pusher');
                    $pusher->trigger('notifications-user-' . $user->getId(), 'new-notifications', 'updates');
                }
            }
        }
        $em->flush();
    }

    public function notificateContactListValidationFinished(ContactList $contactList)
    {

        $em = $this->em;
        $user = $contactList->getAssignee();

        $userNotif = new UserNotification();
        $userNotif->setUser($user);
        $userNotif->setType(UserNotification::type_contact_list);
        $userNotif->setUrl($this->container->get("router")->generate('contactlist_show', array("id" => $contactList->getId())));
        $userNotif->setMessage("El proceso de validación de la lista de contactos " . $contactList->getName() . " ha finalizado.");
        $em->persist($userNotif);
        $em->flush();

        /* real time notifications */
        $pusher = $this->container->get('lopi_pusher.pusher');
        $pusher->trigger('notifications-user-' . $user->getId(), 'new-notifications', 'updates');
    }

    public function notificateContactListImportFinished(ContactList $contactList)
    {

        $em = $this->em;
        $user = $contactList->getAssignee();

        $userNotif = new UserNotification();
        $userNotif->setUser($user);
        $userNotif->setType(UserNotification::type_contact_list);
        $userNotif->setUrl($this->container->get("router")->generate('contactlist_show', array("id" => $contactList->getId())));
        $userNotif->setMessage("Lista de contactos importada -  " . $contactList->getName());
        $em->persist($userNotif);
        $em->flush();

        /* real time notifications */
        $pusher = $this->container->get('lopi_pusher.pusher');
        $pusher->trigger('notifications-user-' . $user->getId(), 'new-notifications', 'updates');
    }

    public function notificateEmailCampaignFinished(CampaignMail $campaign)
    {

        $em = $this->em;
        $user = $campaign->getAssignee();

        $userNotif = new UserNotification();
        $userNotif->setUser($user);
        $userNotif->setType(UserNotification::type_campaign_email);
        $userNotif->setUrl($this->container->get("router")->generate('campaignmail_show', array("id" => $campaign->getId())));
        $userNotif->setMessage("Campaña finalizada -  " . $campaign->getName());
        $em->persist($userNotif);
        $em->flush();

        /* real time notifications */
        $pusher = $this->container->get('lopi_pusher.pusher');
        $pusher->trigger('notifications-user-' . $user->getId(), 'new-notifications', 'updates');
    }
}
