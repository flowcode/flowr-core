<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 5/21/16
 * Time: 4:02 PM
 */

namespace Flower\CoreBundle\Entity;


interface NotificationChannel
{
    function getName();

    function getType();

    function getParameters();

    /**
     * @param $parameterName
     * @return NotificationChannelParameter
     */
    function getParameter($parameterName);
}