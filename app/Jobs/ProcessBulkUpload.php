<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
        ini_set('memory_limit', '1G');
        Log::info('ProcessBulkUpload called');
        
        // Get the file from storage
        $file = Storage::path($this->filePath);
        $inputFileType = IOFactory::identify($file);
        $reader = IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        $questions = [];
        foreach ($worksheet->getRowIterator(2) as $row) { // Start from the second row
            $cellIterator = $row->getCellIterator();
            $rowData = [];

            $count = 0;
            foreach ($cellIterator as $cell) {
                $cellValue = $cell->getValue();
                if ($count >= 20) {
                    break;  // Exit the loop if count exceeds 20
                }
                $rowData[] = $cellValue;
                $count++;
            }

            if (
                !empty($rowData[0]) && !empty($rowData[1]) && !empty($rowData[2]) &&
                !empty($rowData[3]) && !empty($rowData[5]) &&
                !empty($rowData[7]) && !empty($rowData[8])
            ) {
                $options = [];
                for ($i = 9, $letter = 'A'; isset($rowData[$i]); $i++, $letter++) {
                    $options[$letter] = $rowData[$i];
                }

                $question = new Questions; 
                
                $question->create([
                    'question_type' => $rowData[0],
                    'standard_id' => $rowData[1],
                    'subject_id' => $rowData[2],
                    'chapter_id' => $rowData[3],
                    'topic_id' => $rowData[4],
                    'questions' => $rowData[5],
                    'questionsImage' => $rowData[6],
                    'correct_answer' => $rowData[7],
                    'correct_marks' => $rowData[8],
                    'options' => json_encode($options),
                ]);
            }else{
                Log::info('error in file!');
                Log::info($rowData);
                break;
            }
        }

        // Cleanup the temporary file
        Storage::delete($this->filePath);
    }
}


//php artisan queue:work --queue=default