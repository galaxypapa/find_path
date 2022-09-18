<?php

namespace App\Command;


use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:find-path',
    description: 'Find the path from a given CSV file',
    aliases: ['app:find_path'],
    hidden: false
)]
class FindPathCommand extends Command
{
    const QUIT = 'QUIT';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the command for finding the path from a given CSV file
     * Eg, php bin/console  app:find-path ./a.csv
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $output->writeln('Program start');
        $output->writeln('CSV file path: ' . $input->getArgument('file_path'));
        $output->writeln('Please input the format as "A F 1000" & followed by ENTER key to find the path');
        $this->waitForProcess();
        return Command::SUCCESS;
    }

    protected function waitForProcess()
    {
        fscanf(STDIN, "%s %s %d", $firstArg, $secondArg, $thirdArg);
        if ($this->isWaitForInput($firstArg)) {
            $this->waitForProcess();
        }
    }

    /**
     * If $input is quit then return false, otherwise true
     *
     * @param $input
     * @return bool
     */
    protected function isWaitForInput($input): bool
    {
        return strtoupper($input) !== self::QUIT;
    }

    /**
     * configure an argument
     */
    protected function configure(): void
    {
        $this->addArgument('file_path', InputArgument::REQUIRED, 'Input of the csv file path is a must.');
    }
}