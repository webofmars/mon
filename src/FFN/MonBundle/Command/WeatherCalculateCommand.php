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
use FFN\MonBundle\Entity\Weather;
use FFN\MonBundle\Common\WeatherCalculator;

/**
 * Command that calculates weather of any object
 */
class WeatherCalculateCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('mon:weather:calculate');
        $this->setDescription('calculates a weathermap of the monitored urls based on a custom logiq');
    }

    public function execute(InputInterface $input, OutputInterface $output) {

        $output->write('--------------------------------------------------------------------------------');
        $output->write('DNS threshold        : ' . (float) $this->getContainer()->getParameter('weather_default_dns_threshold'));
        $output->write('TCP threshold        : ' . (float) $this->getContainer()->getParameter('weather_default_tcp_threshold'));
        $output->write('1st packet threshold : ' . (float) $this->getContainer()->getParameter('weather_default_first_packet_threshold'));
        $output->write('Total time threshold : ' . (float) $this->getContainer()->getParameter('weather_default_total_time_threshold'));
        $output->write('Very good score : ' . $this->getContainer()->getParameter('weather_very_good_score'));
        $output->write('Average score   : ' . $this->getContainer()->getParameter('weather_average_score'));
        $output->write('Poor score      : ' . $this->getContainer()->getParameter('weather_poor_score'));
        $output->write('--------------------------------------------------------------------------------');
        
        /* looping though controls, through scenarios, to projects */

        // get all the controls
        $em = $this->getContainer()->get('doctrine')->getManager();
        $controls = $em->getRepository("FFNMonBundle:Control")->findAll();

        // Controls
        foreach ($controls as $control) {

            $captures = $em->getRepository("FFNMonBundle:Capture")->findLastsByControl($control->getId(), $this->getContainer()->getParameter('weather_default_window_size'));

            $output->writeln('+ calculating score for control #' . $control->getId());
            $score = 0;

            foreach ($captures as $capture) {
                $output->write('  + calculating score for capture #' . $capture->getId().': ');
                $score = $score + WeatherCalculator::getWeatherScore($capture,
                                                            $this->getContainer()->getParameter('weather_default_valid_codes'),
                                                            (float) $this->getContainer()->getParameter('weather_default_dns_threshold'),
                                                            (float) $this->getContainer()->getParameter('weather_default_tcp_threshold'),
                                                            (float) $this->getContainer()->getParameter('weather_default_first_packet_threshold'),
                                                            (float) $this->getContainer()->getParameter('weather_default_total_time_threshold'));
                $output->writeln($score);
            }
            $score = $score/count($captures);
            $output->writeln('  + average score: '.$score);

            if ($score >= $this->getContainer()->getParameter('weather_very_good_score')) {
                $ctrl_weather = Weather::WEATHER_SUNNY;
            } elseif ($score >= $this->getContainer()->getParameter('weather_average_score')) {
                $ctrl_weather = Weather::WEATHER_MIXED;
            } elseif ($score >= $this->getContainer()->getParameter('weather_poor_score')) {
                $ctrl_weather = Weather::WEATHER_RAIN;
            } else {
                $ctrl_weather = Weather::WEATHER_STORM;
            }

            // $output->writeln("    + weather: $ctrl_weather");

            $this->updateWeather(Weather::OBJECT_TYPE_CONTROL, $capture->getControl()->getId(), $ctrl_weather);
        }

        // scenarios
        $scenarios = $em->getRepository("FFNMonBundle:Scenario")->findAll();

        foreach ($scenarios as $scenario) {
            $sc_weather = Weather::WEATHER_UNKNOWN;
            $output->write('+ calculating score for scenario #' . $scenario->getId().": ");
            foreach ($scenario->getControls() as $control) {

                $control_weather = $em->getRepository("FFNMonBundle:Weather")->findOneByControl($control->getId());
                $sc_weather = min($sc_weather, $control_weather->getWeatherState());
            }
            $output->writeln($sc_weather);

            $this->updateWeather(Weather::OBJECT_TYPE_SCENARIO, $scenario->getId(), $sc_weather);
        }

        // projects
        $projects = $em->getRepository("FFNMonBundle:Project")->findAll();
        foreach ($projects as $project) {
            $pj_weather = Weather::WEATHER_UNKNOWN;
            $output->write('+ calculating score for project #' . $project->getId().': ');
            foreach ($project->getScenarios() as $scenario) {
                $scenario_weather = $em->getRepository("FFNMonBundle:Weather")->findOneByScenario($scenario->getId());
                $pj_weather = \min($pj_weather, $scenario_weather->getWeatherState());
            }
            $output->writeln($pj_weather);

            $this->updateWeather(Weather::OBJECT_TYPE_PROJECT, $project->getId(), $pj_weather);
        }
    }

    private function updateWeather($objectType, $refIdObject, $weatherStatus) {

        $em = $this->getContainer()->get('doctrine')->getManager();
        $weather = $em->getRepository("FFNMonBundle:Weather")->findOneBy(array('objectType' => $objectType,
                                                                'refIdObject' => $refIdObject));
        if ($weather === NULL) {

            $weather = new Weather();
            $weather->setObjectType($objectType);
            $weather->setRefIdObject($refIdObject);
        }

        $weather->setWeatherState($weatherStatus);
        $em->persist($weather);
        $em->flush();
    }
}
