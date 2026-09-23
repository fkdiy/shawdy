<?php

namespace App\Command;

use App\Repository\ShortUrlRepository;
use App\Service\ShortUrlDeleter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:short-url:delete',
    description: 'Deletes a short URL and removes it from the redirect read model.',
)]
final class DeleteShortUrlCommand extends Command
{
    public function __construct(
        private ShortUrlRepository $shortUrlRepository,
        private ShortUrlDeleter $deletionService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'shortCode',
                InputArgument::REQUIRED,
                'The short code to delete',
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Delete without confirmation',
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $io = new SymfonyStyle($input, $output);

        $shortCode = trim(
            (string) $input->getArgument('shortCode')
        );

        if (!preg_match('/^[A-Za-z0-9]+$/', $shortCode)) {
            $io->error('Invalid short code.');

            return Command::INVALID;
        }

        $shortUrl = $this->shortUrlRepository->findOneBy([
            'shortCode' => $shortCode,
        ]);

        if ($shortUrl !== null) {
            $io->section('Short URL');

            $io->definitionList(
                ['Short code' => $shortCode],
                ['Target URL' => $shortUrl->getTargetUrl()],
            );

            if (
                !$input->getOption('force')
                && !$io->confirm(
                    'Delete this short URL?',
                    false,
                )
            ) {
                $io->warning('Deletion cancelled.');

                return Command::SUCCESS;
            }
        } else {
            $io->warning(sprintf(
                'Short URL "%s" was not found in MariaDB. '
                .'Redis cleanup will still be scheduled.',
                $shortCode,
            ));

            if (
                !$input->getOption('force')
                && !$io->confirm(
                    'Continue with Redis cleanup?',
                    false,
                )
            ) {
                $io->warning('Cleanup cancelled.');

                return Command::SUCCESS;
            }
        }

        try {
            $deleted = $this->deletionService->delete($shortCode);
        } catch (\Throwable $exception) {
            $io->error(sprintf(
                'Could not delete short URL "%s": %s',
                $shortCode,
                $exception->getMessage(),
            ));

            return Command::FAILURE;
        }

        if ($deleted) {
            $io->success(sprintf(
                'Short URL "%s" was deleted. '
                .'Redis cleanup has been scheduled.',
                $shortCode,
            ));
        } else {
            $io->success(sprintf(
                'Redis cleanup for "%s" has been scheduled.',
                $shortCode,
            ));
        }

        return Command::SUCCESS;
    }
}
