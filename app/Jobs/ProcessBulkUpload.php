<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use App\Models\Questions;
use Illuminate\Support\Facades\Storage;
use Log;

class ProcessBulkUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '8G');
        Log::info('ProcessBulkUpload called');
        
        // Get the file from storage
        $file = Storage::path($this->filePath);
        $inputFileType = IOFactory::identify($file);
        $reader = IOFactory::createReader($inputFileType);
        
        // Create a chunk read filter
        $chunkFilter = new ChunkReadFilter();

        $chunkSize = 1000;
        $startRow = 2; // Assuming header is in the first row, data starts from the second row
        
        $reader->setReadFilter($chunkFilter);

        // Loop through chunks
        for ($start = $startRow; $start <= $reader->listWorksheetInfo($file)[0]['totalRows']; $start += $chunkSize) {
            $chunkFilter->setRows($start, $chunkSize);
            $spreadsheet = $reader->load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $columns = [];

            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $rowData = [];

                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                $columns[] = $rowData;
            }

            $result = array_slice($columns, 1);

            if (count($result) > 0) {
                foreach ($result as $column) {
                    $question = new Questions;

                    if (
                        !empty($column[0]) && !empty($column[1]) && !empty($column[2]) &&
                        !empty($column[3]) && !empty($column[5]) &&
                        !empty($column[7]) && !empty($column[8])
                    ) {
                        $question->question_type = $column[0];
                        $question->standard_id = $column[1];
                        $question->subject_id = $column[2];
                        $question->chapter_id = $column[3];
                        $question->topic_id = $column[4];
                        $question->questions = $column[5];
                        $question->questionsImage = $column[6];
                        $question->correct_answer = $column[7];
                        $question->correct_marks = $column[8];

                        $options = [];
                        for ($i = 9, $letter = 'A'; isset($column[$i]); $i++, $letter++) {
                            // Ensure the current index exists and is not null
                            if (isset($column[$i])) {
                                // Process $column[$i] here
                                $options[$letter] = $column[$i];
                            }
                        }

                        $question->options = json_encode($options);
                        $question->save();
                    } else {
                        // Log::info('86 ProcessBulkUpload, There is an error in the file!');
                        // Log::info($column);
                    }
                }
            } else {
                // Log error or handle accordingly
            }
        }

        // Cleanup the temporary file
        Storage::delete($this->filePath);
    }
}

// ChunkReadFilter class definition
class ChunkReadFilter implements IReadFilter
{
    private $startRow = 0;
    private $endRow = 0;

    public function setRows($startRow, $chunkSize)
    {
        $this->startRow = $startRow;
        $this->endRow = $startRow + $chunkSize - 1;
    }

    public function readCell($column, $row, $worksheetName = '')
    {
        if ($row >= $this->startRow && $row <= $this->endRow) {
            return true;
        }
        return false;
    }
}

// php artisan queue:work --queue=default