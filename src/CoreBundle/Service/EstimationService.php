<?php

namespace Flower\CoreBundle\Service;

use Flower\ModelBundle\Entity\Project\Estimation;

/**
 * Description of EstimationService
 *
 * @author Juan Manuel Aguero <jaguero@flowcode.com.ar>
 */
class EstimationService
{

    public function getProcessedData(Estimation $estimation){

        $items = array();
        $totalHours = array(
            'optimistic' => 0,
            'pesimistic' => 0,
            'average' => 0,
        );
        $adminHours = array(
            'optimistic' => 0,
            'pesimistic' => 0,
            'average' => 0,
        );
        $testingHours = array(
            'optimistic' => 0,
            'pesimistic' => 0,
            'average' => 0,
        );
        foreach ($estimation->getItems() as $item) {
            $itemArr = array(
                'id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'optimistic' => $item->getOptimistic(),
                'pesimistic' => $item->getPesimistic(),
                'average' => (($item->getOptimistic() + $item->getPesimistic())/2),
            );
            $items[] = $itemArr;
            $totalHours['optimistic'] += $item->getOptimistic();
            $totalHours['pesimistic'] += $item->getPesimistic();
            $totalHours['average'] += $itemArr['average'];
        }
        $adminHours = array(
            'optimistic' => $this->getFNum((($totalHours['optimistic'] * $estimation->getRatioAdmin())/100)),
            'pesimistic' => $this->getFNum((($totalHours['pesimistic'] * $estimation->getRatioAdmin())/100)),
            'average' => $this->getFNum((($totalHours['average'] * $estimation->getRatioAdmin())/100)),
        );
        $testingHours = array(
            'optimistic' => $this->getFNum((($totalHours['optimistic'] * $estimation->getRatioTesting())/100)),
            'pesimistic' => $this->getFNum((($totalHours['pesimistic'] * $estimation->getRatioTesting())/100)),
            'average' => $this->getFNum((($totalHours['average'] * $estimation->getRatioTesting())/100)),
        );
        $totalWorkHours = array(
            'optimistic' => $totalHours['optimistic'] + $adminHours['optimistic'] + $testingHours['optimistic'],
            'pesimistic' => $totalHours['pesimistic'] + $adminHours['pesimistic'] + $testingHours['pesimistic'],
            'average' => $totalHours['average'] + $adminHours['average'] + $testingHours['average'],
        );
        $totalDays = array(
            'optimistic' => $this->getFDays($totalWorkHours['optimistic'] / $estimation->getDailyWorkingHours()),
            'pesimistic' => $this->getFDays($totalWorkHours['pesimistic'] / $estimation->getDailyWorkingHours()),
            'average' => $this->getFDays($totalWorkHours['average'] / $estimation->getDailyWorkingHours()),
        );

        /* arrange results */
        $estimationProcessed = array(
            'items' => $items,
            'totalHours' => $totalHours,
            'adminHours' => $adminHours,
            'testingHours' => $testingHours,
            'totalWorkHours' => $totalWorkHours,
            'totalDays' => $totalDays,
        );

        return $estimationProcessed;
    }

    /**
     * Get formated number.
     * @param  float|string $number input number
     * @return string         formated number.
     */
    public function getFNum($number)
    {
        return number_format($number, 2, '.', '');
    }

    /**
     * Get formated days.
     * @param  float|string $number input number
     * @return string         formated days.
     */
    public function getFDays($days)
    {
        return ceil($days);
    }


}
