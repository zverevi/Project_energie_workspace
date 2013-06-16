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
        $capteur = new Capteur();
        $capteur->setCapteurName($applianceId);
        $houseHold->addCapteur($capteur);
        $houseHold->save();

        while ($line = fgets($handle)) {
            $mesureArray = explode("\t", $line);
            $timestamp = date_create_from_format('j/m/y i:H', $mesureArray[0]." ".$mesureArray[1]);
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


    }
}