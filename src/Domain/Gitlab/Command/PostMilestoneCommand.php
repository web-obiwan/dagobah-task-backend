<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Command;

use App\Domain\Gitlab\Client\GitlabClient;
use App\Domain\Gitlab\Handler\CreateMiletoneHandler;
use App\Entity\Project;
use App\Entity\Sprint;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'gitlab:post-milestone'
)]
class PostMilestoneCommand extends Command
{
    public function __construct(
        protected readonly GitlabClient $client,
        private readonly CreateMiletoneHandler $handler,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'id-project',
                null,
                InputOption::VALUE_REQUIRED
            )
            ->addOption(
                'id-sprint',
                null,
                InputOption::VALUE_REQUIRED
            )
        ;
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $idProject = $input->getOption('id-project');
        $idSprint = $input->getOption('id-sprint');

        $project = $this->em->getRepository(Project::class)->find($idProject);
        $sprint = $this->em->getRepository(Sprint::class)->find($idSprint);

        $this->handler->process($project, $sprint);

        return Command::SUCCESS;
    }
}
