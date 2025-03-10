<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Command;

use Gitlab\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'gitlab:get-version'
)]
class GetVersionCommand extends Command
{
    public function __construct(
        protected readonly Client $client
    ) {
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $version = $this->client->version();
        $io->title('GITLAB Version');
        print_r($version->show());

        return Command::SUCCESS;
    }
}
