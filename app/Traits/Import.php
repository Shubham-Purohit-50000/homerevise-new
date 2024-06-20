<?php

namespace App\Traits;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;


trait Import
{
    public function ImportFile($file): array
    {
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $columns = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = array_map(function ($cell) {
                return $cell->getValue();
            }, iterator_to_array($row->getCellIterator()));
            $columns[] = $rowData;
        }
        return array_slice($columns, 1);
    }
    public function generateSampleFile($file_name)
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $columns = array_diff(Schema::getColumnListing($file_name), ['id','created_at','updated_at']);
        $sheet->fromArray([$columns], null, 'A1');
        // Save the sample file

        $filename =$file_name . '_sample.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        $path= public_path();
        $file = fopen($path.'/'.$filename, 'r');
        $response = response()->make(stream_get_contents($file), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ]);
        fclose($file);
        unlink($filename);
        return $response;
    }
}
