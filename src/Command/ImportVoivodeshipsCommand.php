<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Voivodeship;
use App\Repository\VoivodeshipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'app:import-voivodeships',
    description: 'Import voivodeships from TERYT data',
)]
class ImportVoivodeshipsCommand extends Command
{
    private const CSV_FILE_PATH = 'var/teryt_data/extracted_voivodeship.csv';
    private const CSV_DELIMITER = ';';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly VoivodeshipRepository $voivodeshipRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Importing voivodeships from TERYT data');

        // Check if the file exists
        $filePath = $this->getProjectDir().'/'.self::CSV_FILE_PATH;
        if (!file_exists($filePath)) {
            $io->error('CSV file not found: '.$filePath);

            return Command::FAILURE;
        }

        // Create slugger for generating slugs
        $slugger = new AsciiSlugger('pl');

        // Read and parse the CSV file
        $handle = fopen($filePath, 'r');
        if (false === $handle) {
            $io->error('Could not open the CSV file.');

            return Command::FAILURE;
        }

        // Skip the header row
        $header = fgetcsv($handle, 0, self::CSV_DELIMITER);
        if (!$header) {
            $io->error('Could not read the CSV header.');
            fclose($handle);

            return Command::FAILURE;
        }

        $count = 0;
        $now = new \DateTimeImmutable();

        // Process each row
        while (($data = fgetcsv($handle, 0, self::CSV_DELIMITER)) !== false) {
            // Expected columns: WOJ;POW;GMI;RODZ;NAZWA;NAZWA_DOD;STAN_NA
            if (count($data) < 7) {
                continue; // Skip incomplete rows
            }

            $externalId = $data[0];
            $name = $data[4];
            $updateDate = \DateTimeImmutable::createFromFormat('Y-m-d', $data[6]);

            if (empty($externalId) || empty($name) || !$updateDate) {
                continue; // Skip rows with missing essential data
            }

            // Check if voivodeship already exists
            $voivodeship = $this->voivodeshipRepository->findByExternalId($externalId);

            if (!$voivodeship) {
                // Create a new voivodeship
                $voivodeship = new Voivodeship(
                    id: Uuid::v7(),
                    name: $name,
                    slug: $slugger->slug($name)->lower()->toString(),
                    externalId: $externalId,
                    externalUpdatedDay: $updateDate,
                    updatedAt: $now,
                );
                ++$count;
            }

            $voivodeship->update(
                name: $name,
                slug: $slugger->slug($name)->lower()->toString(),
                externalId: $externalId,
                updateDate: $updateDate,
                updatedAt: $now,
            );

            $this->entityManager->persist($voivodeship);
        }

        fclose($handle);

        // Flush changes to database
        $this->entityManager->flush();

        $io->success(sprintf('Successfully imported %d voivodeships.', $count));

        return Command::SUCCESS;
    }

    private function getProjectDir(): string
    {
        return dirname(__DIR__, 2);
    }
}
