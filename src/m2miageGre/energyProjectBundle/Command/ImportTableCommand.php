<?php

namespace m2miageGre\energyProjectBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportTableCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this
            ->setName("irise:createTable")
            ->setDescription("Extract Data from Irise file and populate DataBase")
            ->addArgument(
                "f",
                InputArgument::REQUIRED,
                "File path of Irise datafile"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('f');

    }
}