<?php

namespace App\Livewire;

use App\Models\Event;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use SplFileObject;

class UploadCSV extends Component
{

    use WithFileUploads;

    // #[Validate('required|file|mimes:csv,txt')]
    #[Validate('required|file')]
    public $csvfile;

    public $message = '';
    public $errorMessage = '';

    public function save()
    {
        // try{
            // $this->validate();
            $path = $this->csvfile->store('csv_uploads', 'public');

            $file = new SplFileObject(storage_path('app/public/'.$path), 'r');
            $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::READ_AHEAD);

            $header = $file->current();

            $file->next();

        $importedCount = 0;
        $skippedCount = 0;

        $rows = [];

        $startTime = time();

        while (!$file->eof()) {
            $row = $file->current();
            if (empty($row) || count($row) !== count($header)) {
                $file->next(); 
                $skippedCount++;
                continue;
            }
            Log::info($row[1]);

            $row[1] = Carbon::parse($row[1])->format('Y-m-d');

            Log::info($row[1]);

            $data = array_combine($header, $row);

            try {
                $data['is_background_image'] = filter_var($data['is_background_image'], FILTER_VALIDATE_BOOLEAN);
                $rows[] = $data;
                $importedCount++;
            } catch (\Exception $e) {
                Log::warning("EventSeeder: Skipping row due to error: " . $e->getMessage() . " Data: " . json_encode($data));
                $skippedCount++;
            }

            $file->next();
        }

        $endTime = time() - $startTime;

        Log::info("Time taken: ". $endTime."ms");

            Event::where('user_id', Auth::user()?->id)->insert($rows);

            $this->message = "CSV file processed and ".$importedCount." events added and ".$skippedCount." events skipped due to error.";
            $this->csvfile = null;

            session()->flash('status', 'Events imported.');

        // } catch(ValidationException $e){
        //     $this->errorMessage = 'Validation Failed: '.$e->getMessage();
        // }
        // catch(Exception $e){
        //     $this->errorMessage = 'An error occurred during upload or processing: '. $e->getMessage();
        // }
    }

    public function render()
    {
        return view('livewire.upload-c-s-v');
    }
}
