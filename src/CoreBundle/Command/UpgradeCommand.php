<?php

namespace Flower\CoreBundle\Command;

use Flower\ModelBundle\Entity\Project\ProjectIteration;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of UpgradeCommand
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class UpgradeCommand extends ContainerAwareCommand
{

    private $entityManager;

    protected function configure()
    {
        $this
            ->setName('flower:upgrade')
            ->setDescription('flower upgrade.');
    }

    /**
     * The method process an saved in a particular folder and import all the contacts to
     * the specified contact list.
     * @author Francisco Memoli <fmemoli@flowcode.com.ar>
     * @date   2015-06-02
     * @param  InputInterface $input Recive as parameters the contact list id and the file name to precess.
     * @param  OutputInterface $output print some Output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get("logger")->info(date("Y-m-d H:i:s") . " - starting upgrade.");

        $this->entityManager = $this->getContainer()->get("doctrine.orm.entity_manager");

        $conn = $this->getEM()->getConnection();


        $projectsSql = "SELECT * FROM project";
        $projectsStmt = $conn->query($projectsSql);

        while ($project = $projectsStmt->fetch()) {
            $output->writeln("Project: " . $project['name']);

            /* project boards */
            $projectsBoardsSql = "SELECT * FROM board b, project_boards pb WHERE b.id = pb.board_id and pb.project_id = " . $project['id'];
            $projectsBoardsStmt = $conn->query($projectsBoardsSql);

            $output->writeln("- boards: ");
            while ($board = $projectsBoardsStmt->fetch()) {
                $output->writeln("-- Board: " . $board['name']);

                $qInsertIteration = 'INSERT INTO project_iteration(project_id, name, status) VALUES(' . $project['id'] . ', "' . $board['name'] . '", 0)';
                $conn->exec($qInsertIteration);
                $iterationId = $conn->lastInsertId();

                $qInsertTaskFilter = 'INSERT INTO task_filter(name, filter, project_iteration_id, private, archived, created, updated) VALUES("default", "project_id=' . $project['id'] . '|project_iteration_id=' . $iterationId . '",' . $iterationId . ', 0, 0, "' . date("Y-m-d H:i:s") . '", "' . date("Y-m-d H:i:s") . '" )';
                $conn->exec($qInsertTaskFilter);

                /* board tasks */
                $boardTasksSql = "SELECT * FROM task WHERE board_id  = " . $board['id'];
                $boardTasksStmt = $conn->query($boardTasksSql);

                while ($task = $boardTasksStmt->fetch()) {
                    $qUpdateTask = 'UPDATE task SET project_id=' . $project['id'] . ', project_iteration_id=' . $iterationId . ' WHERE id = ' . $task['id'];
                    $conn->exec($qUpdateTask);
                }

            }

        }

        /*
        $boardsSql = "SELECT * FROM board b LEFT JOIN project_boards pb ON b.id = pb.board_id WHERE  pb.project_id IS NULL";
        $boardsStmt = $conn->query($boardsSql);

        $output->writeln("boards: ");
        while ($board = $boardsStmt->fetch()) {
            $output->writeln("-- Board: " . $board['name']);

            $iterationId = $conn->lastInsertId();


            $boardTasksSql = "SELECT * FROM task WHERE board_id  = " . $board['id'];
            $boardTasksStmt = $conn->query($boardTasksSql);

            while ($task = $boardTasksStmt->fetch()) {
                $qUpdateTask = 'UPDATE task SET project_id=' . $project['id'] . ', project_iteration_id=' . $iterationId . ' WHERE id = ' . $task['id'];
                $conn->exec($qUpdateTask);
            }

        }
*/

        $this->getContainer()->get("logger")->info(date("Y-m-d H:i:s") . " - flowr upgraded.");
    }

    /**
     *
     * @return \Doctrine\ORM\EntityManagerInterface em
     */
    protected function getEM()
    {
        return $this->entityManager;
    }

}
