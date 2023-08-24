<?php

function generateBatchNumber($prefix = 'BATCH') {
    $uniqueIdentifier = substr(uniqid(), 0, 6); // Generates a unique identifier
    $currentDate = date('Ymd');   // Current date in YYYYMMDD format

    $batchNumber = "{$prefix}_{$currentDate}_{$uniqueIdentifier}";

    return $batchNumber;
}

function generateMedicineCode($prefix = 'MED') {
    $uniqueIdentifier = uniqid(); // Generates a unique identifier

    // Extract the first 6 characters of the unique identifier
    $shortIdentifier = substr($uniqueIdentifier, 0, 6);

    $medicineCode = "{$prefix}{$shortIdentifier}";

    return $medicineCode;
}
