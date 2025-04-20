<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Locality;
use App\Entity\Voivodeship;
use App\Repository\LocalityRepository;
use App\Repository\VoivodeshipRepository;
use Doctrine\ORM\EntityManagerInterface as ORMEntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'app:import-localities',
    description: 'Import localities from TERYT data',
)]
class ImportLocalitiesCommand extends Command
{
    // CSV File Structure: WOJ;POW;GMI;RODZ_GMI;RM;MZ;NAZWA;SYM;SYMPOD;STAN_NA
    private const CSV_FILE_PATH = 'var/teryt_data/SIMC_Urzedowy_2025-04-18.csv';
    private const CSV_DELIMITER = ';';
    private const BATCH_SIZE = 100; // Process in batches

    public function __construct(
        private readonly ORMEntityManagerInterface $entityManager,
        private readonly LocalityRepository $localityRepository,
        private readonly VoivodeshipRepository $voivodeshipRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Importing localities from TERYT data');

        // Disable SQL logger for this command to save memory
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $filePath = $this->getProjectDir().'/'.self::CSV_FILE_PATH;
        if (!file_exists($filePath)) {
            $io->error('CSV file not found: '.$filePath);

            return Command::FAILURE;
        }

        $slugger = new AsciiSlugger('pl');
        $handle = fopen($filePath, 'r');
        if (false === $handle) {
            $io->error('Could not open the CSV file.');

            return Command::FAILURE;
        }

        // Skip the header row
        $header = fgetcsv($handle, 0, self::CSV_DELIMITER);
        if (!$header || count($header) < 10) { // Check header columns count
            $io->error('Could not read the CSV header or header is invalid.');
            fclose($handle);

            return Command::FAILURE;
        }

        $count = 0;
        $newCount = 0;
        $updatedCount = 0;
        $skippedVoivodeship = 0;
        $now = new \DateTimeImmutable();
        $processedRows = 0;

        // Process each row
        while (($data = fgetcsv($handle, 0, self::CSV_DELIMITER)) !== false) {
            ++$processedRows;
            // Expected columns: WOJ;POW;GMI;RODZ_GMI;RM;MZ;NAZWA;SYM;SYMPOD;STAN_NA
            if (count($data) < 10) {
                $ddd = count($data);
                $asString = implode(',', $data);
                $io->warning(sprintf('Skipping incomplete row %d'." Data count: {$ddd}. Data: {$asString}", $processedRows + 1)); // +1 because header was read

                continue; // Skip incomplete rows
            }

            $voivodeshipExternalId = $data[0];
            $localityTypeCode = $data[4];
            $localityName = $data[6];
            $localityExternalId = $data[7]; // SYM is the main ID
            $updateDateStr = $data[9];

            // Extract additional data for JSON field
            $externalData = [
                'WOJ' => $data[0],
                'POW' => $data[1] ?? null,
                'GMI' => $data[2] ?? null,
                'RODZ_GMI' => $data[3] ?? null,
                'RM' => $data[4],
                'MZ' => $data[5] ?? null,
                'SYM' => $data[7],
                'SYMPOD' => $data[8] ?? null,
            ];

            $updateDate = \DateTimeImmutable::createFromFormat('Y-m-d', $updateDateStr);

            if (empty($voivodeshipExternalId) || empty($localityName) || empty($localityExternalId) || empty($localityTypeCode) || !$updateDate) {
                $io->warning(sprintf('Skipping row %d due to missing essential data', $processedRows + 1));

                continue; // Skip rows with missing essential data
            }

            // Check if locality already exists by external ID (SYM)
            $locality = $this->localityRepository->findByExternalId($localityExternalId);
            $slug = $slugger->slug($localityName)->lower()->toString();

            /** @var Voivodeship $voivodeship */
            $voivodeship = $this->voivodeshipRepository->findByExternalId($voivodeshipExternalId);

            if (!$locality) {
                // Create a new locality
                $locality = new Locality(
                    id: Uuid::v7(),
                    voivodeship: $voivodeship,
                    name: $localityName,
                    slug: $slug,
                    externalId: $localityExternalId,
                    typeCode: $localityTypeCode,
                    externalData: $externalData,
                    externalUpdatedDay: $updateDate,
                    updatedAt: $now,
                );
                $this->entityManager->persist($locality);
                ++$newCount;
            } else {
                // Update existing locality
                $locality->update(
                    voivodeship: $voivodeship,
                    name: $localityName,
                    slug: $slug,
                    externalId: $localityExternalId,
                    typeCode: $localityTypeCode,
                    externalData: $externalData,
                    externalUpdatedDay: $updateDate,
                    updatedAt: $now,
                );
                // Persist is not strictly needed for updates unless the object was detached,
                // but it doesn't hurt.
                ++$updatedCount;
            }

            ++$count;

            // Flush and clear periodically to manage memory
            if (($count % self::BATCH_SIZE) === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear(); // Detach all objects from Doctrine
            }
        }

        fclose($handle);

        // Flush remaining changes
        $this->entityManager->flush();
        $this->entityManager->clear();

        if ($skippedVoivodeship > 0) {
            $io->warning(sprintf('Skipped %d rows because their voivodeship (WOJ) was not found. Ensure voivodeships are imported first.', $skippedVoivodeship));
        }
        $io->success(sprintf('Successfully processed %d localities. New: %d, Updated: %d.', $count, $newCount, $updatedCount));

        return Command::SUCCESS;
    }

    private function getProjectDir(): string
    {
        // Adjust the number of levels up based on the command's location
        return dirname(__DIR__, 2);
    }
}
