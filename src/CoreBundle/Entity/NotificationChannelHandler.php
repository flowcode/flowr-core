<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 5/21/16
 * Time: 3:58 PM
 */

namespace Flower\CoreBundle\Entity;


interface NotificationChannelHandler
{
    function handle(NotificationChannel $notificationChannel, array $params);
}