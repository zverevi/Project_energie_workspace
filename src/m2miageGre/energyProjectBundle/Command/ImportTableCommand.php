<?php

namespace m2miageGre\energyProjectBundle\Command;


use m2miageGre\energyProjectBundle\Model\Capteur;
use m2miageGre\energyProjectBundle\Model\HouseHold;
use m2miageGre\energyProjectBundle\Model\Mesure;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Propel;

class ImportTableCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this
            ->setName("irise:createTable")
            ->setDescription("Extract Data from Irise file and populate DataBase")
            ->addArgument(
                "version",
                InputArgument::REQUIRED,
                "Algorithm version: v1 v2 v3 or v4"
            )
            ->addArgument(
                "filePath",
                InputArgument::REQUIRED,
                "File path of Irise datafile"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // optimizations
        gc_enable();
        Propel::getConnection()->useDebug(false);
        Propel::disableInstancePooling();
        $output->writeln(memory_get_usage());

        $filePath = $input->getArgument('filePath');
        $output->writeln("Opening: ".$filePath);
        $handle = fopen($filePath, "r");

        for ($i=0;$i<5;$i++) {
            $header[] =  fgets($handle);
        }

        $houseHoldId = trim(explode(':', $header[1])[1]);
        $applianceId = trim(explode(':', $header[2])[1]);

        $houseHold = new HouseHold();
        $houseHold->setId($houseHoldId);
        if ($houseHold->validate()) {
            $houseHold->save();
        } else {
            foreach ($houseHold->getValidationFailures() as $failure) {
                echo $failure->getMessage() . "\n";
            }
        }
        $capteur = new Capteur();
        $capteur->setCapteurName($applianceId);
        $capteur->setHouseholdId($houseHold->getId());
        if ($capteur->validate()) {
            $capteur->save();
        } else {
            foreach ($houseHold->getValidationFailures() as $failure) {
                echo $failure->getMessage() . "\n";
                exit;
            }
        }


        while ($line = fgets($handle)) {
            switch ($input->getArgument("version")) {
                case "v1" :
                    $this->v1($input, $output, $line, $capteur);
                    break;
                case "v2" :
                    $this->v2($input, $output, $line, $capteur);
                    break;
                case "v3" :
                    $this->v3($input, $output, $line, $capteur);
                    break;
                case "v4" :
                    $this->v4($input, $output, $line, $capteur);
                    break;
                default:
                    $output->writeln("This version don't exists");
            }
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param String $line
     * @param Capteur $capteur
     */
    protected function v1(InputInterface $input, OutputInterface $output, $line, Capteur $capteur)
    {
        $mesureArray = explode("\t", $line);
        $timestamp = date_create_from_format('j/m/y H:i', $mesureArray[0]." ".$mesureArray[1]);
        $state = $mesureArray[2];
        $energy = intval($mesureArray[3]);
        $mesure = new Mesure();
        $mesure->setTimestamp($timestamp);
        $mesure->setEnergy($energy);
        $mesure->setState($state);
        $mesure->setCapteurId($capteur->getId());

        $output->writeln("saving ".$mesure->getTimestamp("Y-m-d H:i"));
        $mesure->save();
        gc_collect_cycles();
    }

    protected function v2(InputInterface $input, OutputInterface $output, $line, Capteur $capteur)
    {
        $mesureArray = explode("\t", $line);
        $timestamp = date_create_from_format('j/m/y H:i', $mesureArray[0]." ".$mesureArray[1]);
        $state = $mesureArray[2];
        $energy = intval($mesureArray[3]);
        if ($energy != 0) {
            $mesure = new Mesure();
            $mesure->setTimestamp($timestamp);
            $mesure->setEnergy($energy);
            $mesure->setState($state);
            $mesure->setCapteurId($capteur->getId());
            $output->writeln("saving ".$mesure->getTimestamp("Y-m-d H:i"));
            $mesure->save();
            gc_collect_cycles();
        }
    }

    protected function v3(InputInterface $input, OutputInterface $output, $line, Capteur $capteur)
    {
        $mesureArray = explode("\t", $line);
        $timestamp = date_create_from_format('j/m/y H:i', $mesureArray[0]." ".$mesureArray[1]);
        $state = $mesureArray[2];
        $energy = intval($mesureArray[3]);
        if ($energy !== $this->lastEnergyValue) {
            $mesure = new Mesure();
            $mesure->setTimestamp($timestamp);
            $mesure->setEnergy($energy);
            $mesure->setState($state);
            $mesure->setCapteurId($capteur->getId());
            $output->writeln("saving ".$mesure->getTimestamp("Y-m-d H:i"));
            $mesure->save();

            $this->lastEnergyValue = $energy;
            gc_collect_cycles();
        }
    }

    protected function v4(InputInterface $input, OutputInterface $output, $line, Capteur $capteur)
    {
        $output->writeln("not yet implemented");
    }

    protected $lastEnergyValue;
}