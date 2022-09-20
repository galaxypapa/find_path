<?php

namespace App\Command;


use App\Service\DeviceManagerInterface;
use App\Service\File\FileLoaderInterface;
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
    protected FileLoaderInterface $fileLoader;
    protected DeviceManagerInterface $deviceManager;
    protected OutputInterface $output;

    public function __construct(FileLoaderInterface $fileLoader, DeviceManagerInterface $deviceManager)
    {
        parent::__construct();
        $this->fileLoader = $fileLoader;
        $this->deviceManager = $deviceManager;
    }

    /**
     * Execute the command for finding the path from a given CSV file
     * Eg, php bin/console  app:find-path latency.csv
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $output->writeln('Program start');
        $output->writeln('CSV file path: ' . $input->getArgument('file_path'));
        $latencyInfoFromCSV = $this->fileLoader->load($input->getArgument('file_path'));
        $output->writeln('Please input the format as "A F 1000" & followed by ENTER key to find the path');
        $this->processInput($latencyInfoFromCSV);
        return Command::SUCCESS;
    }

    /**
     * Recursive method to find the path continuously
     *
     *
     */
    protected function processInput(array $latencyInfoFromCSV)
    {
        echo "Input: ";
        fscanf(STDIN, "%s %s %d", $from, $to, $latency);
        if (empty($from) || empty($to) || empty($latency) || !is_int($latency)) {
            if (isset($from))
                $from = strtoupper($from);
            if ($from !== self::QUIT) {
                $this->output->writeln('Output: Input error... Syntax: "[from] [to] [latency]", type "QUIT" to terminate script');
            }
        } else {
            $result = $this->deviceManager->shortestPath($from, $to, $this->deviceManager->storeAsGraph($latencyInfoFromCSV));
            $output = $result['latency'] > $latency ? 'output: Path not found' : $this->formatOutput($result);
            $this->output->writeln($output);
        }
        if ($this->isWaitForInput($from)) {
            $this->processInput($latencyInfoFromCSV);
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
        if (isset($input))
            $input = strtoupper($input);
        return $input !== self::QUIT;
    }

    /**
     * configure an argument
     */
    protected function configure(): void
    {
        $this->addArgument('file_path', InputArgument::REQUIRED, 'Input of the csv file path is a must.');
    }

    /**
     * Format the output
     *
     * @param array $result
     * @return string
     */
    private function formatOutput(array $result): string
    {
        $path = '';
        $stack = $result['path'];
        $stack->rewind();
        while ($stack->valid()) {
            $path .= $stack->current() . ' => ';
            $stack->next();
        }
        return sprintf("output:%s%s", $path, $result['latency']);
    }
}