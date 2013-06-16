<?php

namespace m2miageGre\energyProjectBundle\Command;


use m2miageGre\energyProjectBundle\Model\Capteur;
use m2miageGre\energyProjectBundle\Model\HouseHold;
use m2miageGre\energyProjectBundle\Model\Mesure;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
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
                "filePath",
                InputArgument::REQUIRED,
                "File path of Irise datafile"
            )
            ->addArgument(
                "version",
                InputArgument::REQUIRED,
                "Algorithm version: v1 v2 v3 or v4"
            )
            ->addArgument(
                "thresold",
                InputArgument::OPTIONAL,
                "thresold for v4"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // optimizations
        gc_enable();
        Propel::getConnection()->useDebug(false);
        Propel::disableInstancePooling();
        $output->writeln(memory_get_usage());
        // file handle
        $filePath = $input->getArgument('filePath');
        $output->writeln("Opening: ".$filePath);
        $handle = fopen($filePath, "r");

        //extract info from 5 first lines
        for ($i=0;$i<5;$i++) {
            $header[] =  fgets($handle);
        }

        $houseHoldId = trim(explode(':', $header[1])[1]);
        $applianceId = trim(explode(':', $header[2])[1]);
        // create entities
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

        $this->thresold = $input->getArgument("thresold");
        while ($line = fgets($handle)) {

            $mesureArray = explode("\t", $line);
            $timestamp = date_create_from_format('j/m/y H:i', $mesureArray[0]." ".$mesureArray[1]);
            $state = $mesureArray[2];
            $energy = intval($mesureArray[3]);
            if ($this->testCreateMesure($input->getArgument("version"), $energy)) {
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
    }

    public function testCreateMesure($version, $energy) {

        switch ($version) {
            case "v1" :
                return true;
            case "v2" :
                return $energy != 0;
            case "v3" :
                return $energy !== $this->lastEnergyValue;
            case "v4" :
                return abs($energy - $this->lastEnergyValue) > $this->thresold;
            default:
                throw new \Exception("version does not exist");
        }
    }

    /**
     * @var integer
     */
    protected $lastEnergyValue;

    /**
     * @var integer
     */
    protected $thresold;
}