#!/usr/bin/env php
<?php

$inputFile = __DIR__.'/../var/teryt_data/TERC_Urzedowy_2025-04-18.csv';
$outputFile = __DIR__.'/../var/teryt_data/extracted_voivodeship.csv';
$filterColumnIndex = 5; // Index for NAZWA_DOD
$filterValue = 'województwo';
$delimiter = ';';

$inputHandle = fopen($inputFile, 'r');
if (false === $inputHandle) {
    echo "Error: Could not open input file: {$inputFile}";

    exit(1);
}

// Check if output file exists and create or overwrite it
if (file_exists($outputFile)) {
    $outputHandle = fopen($outputFile, 'w'); // 'w' mode will overwrite existing content
} else {
    // Ensure directory exists
    $outputDir = dirname($outputFile);
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }
    $outputHandle = fopen($outputFile, 'w'); // Create new file
}

if (false === $outputHandle) {
    echo "Error: Could not open output file for writing: {$outputFile}";
    fclose($inputHandle);

    exit(1);
}

// Read and write header
$header = fgetcsv($inputHandle, 0, $delimiter);
if (false === $header) {
    echo "Error: Could not read header from input file: {$inputFile}
";
    fclose($inputHandle);
    fclose($outputHandle);

    exit(1);
}
fputcsv($outputHandle, $header, $delimiter);

// Process data rows
while (($row = fgetcsv($inputHandle, 0, $delimiter)) !== false) {
    if (isset($row[$filterColumnIndex]) && $row[$filterColumnIndex] === $filterValue) {
        fputcsv($outputHandle, $row, $delimiter);
    }
}

fclose($inputHandle);
fclose($outputHandle);

echo "Successfully extracted voivodeships to: {$outputFile}";

exit(0);
