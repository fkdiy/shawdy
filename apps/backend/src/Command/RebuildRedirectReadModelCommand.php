<?php

namespace App\Command;

use App\Contract\RedirectReadModel;
use App\Service\RedirectReadModelRebuilder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:redirect-read-model:rebuild',
    description: 'Rebuilds the Redis redirect read model from the authoritative source.',
)]
final class RebuildRedirectReadModelCommand extends Command
{
    public function __construct(
        private RedirectReadModelRebuilder $rebuilder,
        private RedirectReadModel $readModel,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'if-uninitialized',
            null,
            InputOption::VALUE_NONE,
            'Only rebuild if the redirect read model is not initialized.',
        );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $io = new SymfonyStyle($input, $output);

        if (
            $input->getOption('if-uninitialized')
            && $this->readModel->isInitialized()
        ) {
            $io->info(
                'Redirect read model is already initialized. Skipping rebuild.'
            );

            return Command::SUCCESS;
        }

        try {
            $count = $this->rebuilder->rebuild();
        } catch (\Throwable $exception) {
            $io->error(sprintf(
                'Could not rebuild redirect read model: %s',
                $exception->getMessage(),
            ));

            return Command::FAILURE;
        }

        $io->success(sprintf(
            'Redirect read model rebuilt successfully. %d entries synchronized.',
            $count,
        ));

        return Command::SUCCESS;
    }
}
