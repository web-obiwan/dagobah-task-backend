<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Command;

use App\Domain\Gitlab\Client\GitlabClient;
use GuzzleHttp\Exception\GuzzleException;
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
        protected readonly GitlabClient $client
    ) {
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $response = $this->client->getVersion();
        $io->title('GITLAB Version');
        $io->text($response->getBody()->getContents());

        return Command::SUCCESS;
    }
}
