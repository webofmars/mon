<?php

namespace FFN\MonBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use FFN\MonBundle\Entity\Capture;
use FFN\MonBundle\Entity\CaptureDetail;
use DateTime;
use DateInterval;
use DateTimeZone;

/**
 * Command that fill up the capture scheduled dates based on the scenario frequencies
 */
class SchedulerUpdateCommand extends ContainerAwareCommand {

  protected function configure() {
    $this->setName('mon:scheduler:update');
    $this->setDescription('update the captures scheduled date based on the scenario frequency');
    $this->addArgument('interval', InputArgument::OPTIONAL, 'Interval to schedule captures for', 60);
    $this->addArgument('round', InputArgument::OPTIONAL, 'Round values in increment of', 10);
  }

  public function execute(InputInterface $input, OutputInterface $output) {

    if (is_null($input->getArgument('interval'))) {
      $output->writeln('error: missing argument interval');
    }

    if (is_null($input->getArgument('round'))) {
      $output->writeln('error: missing argument interval');
    }

    $start = new DateTime('@' . $this->minutes_round(time(), $input->getArgument('round')), new DateTimeZone('UTC'));
    $stop = clone $start;
    $stop->modify('+'.$input->getArgument('interval').' minutes');

    $output->writeln('- Updating capture table between ' . $start->format('Y-m-d H:i:s') . ' and ' . $stop->format('Y-m-d H:i:s') . ' ...');

    // get all the scenarios
    $em = $this->getContainer()->get('doctrine')->getManager();
    $scenarios = $em->getRepository("FFNMonBundle:Scenario")->findAll();

    foreach ($scenarios as $scenario) {
      $output->writeln('-- updating schedule for scenario # ' . $scenario->getId() . ' (' . $scenario->getName() . ')');

      // get all controls from the current scenario
      $controls = $scenario->getControls();

      foreach ($controls as $control) {
        $output->write("--- updating schedule for control #" . $control->getId());
        $output->writeln(" (" . $control->getName() . ").");
        // create schedules in DB
        $ctrlStart = clone $start;
        $ctrlStop = clone $stop;
        while ($ctrlStart < $ctrlStop) {
          if ($this->scheduleCapture($ctrlStart, $control) === true) {
            $output->writeln('---- added capture at ' . $ctrlStart->format('Y-m-d H:i:s'));
          } else {
            $output->writeln('---- error while adding capture at ' . $ctrlStart->format('Y-m-d H:i:s'));
          }
          $ctrlStart->add(new DateInterval('PT' . $scenario->getFrequency() . 'M'));
        }
      }
    }
  }

  // TODO: a externaliser
  protected function scheduleCapture($dateTime, $control) {

    try {
      // try to add a new scheduled capture to do
      $em = $this->getContainer()->get('doctrine')->getManager();
      $capDetail = new CaptureDetail();
      $cap = new capture();
      $cap->setDateScheduled($dateTime);
      $cap->setControl($control);
      $em->persist($cap);
      $em->flush();
      return true;
    } catch (\Exception $e) {
      // if scheduler is launched too close from earlier one, duplicate entries could happen
      return false;
    }
  }

  // TODO: a externaliser
  protected function minutes_round($epoch, $minutes = '10') {
    $rounded = round($epoch / ($minutes * 60)) * ($minutes * 60);
    return $rounded;
  }

}

?>
