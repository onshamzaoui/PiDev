<?php

namespace App\Controller;


use App\Entity\Don;
use App\Entity\Recyclage;
use App\Entity\TypeDechet;
use League\Csv\Writer;

class ExportService
{
    public function exportToCSV($data)
    {
        $csv = Writer::createFromString('');

        // Write headers
        $csv->insertOne(['ID', 'Quantite du don en kg', 'Description', 'Type de dechet']);

        // Write data rows
        foreach ($data as $row) {
            $csv->insertOne([$row->getId(), $row->getQuantiteDon(), $row->getDescription(), $row->getTypeDechet()->getNomDechet()]);
        }

        return $csv->__toString();
    }
}
