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

    #[Validate('required|file|mimes:csv,txt|max:5120')]
    public $csvfile;

    public $message = '';
    public $errorMessage = '';

    public function save()
    {
        $this->validate();

        $user = Auth::user();
        if (!$user) {
            $this->errorMessage = 'You must be logged in to import events.';
            return;
        }

        try {
            $path = $this->csvfile->store('csv_uploads', 'local');
            $fullPath = storage_path('app/' . $path);

            if (!file_exists($fullPath)) {
                $this->errorMessage = 'Uploaded file could not be found.';
                return;
            }

            $file = new SplFileObject($fullPath, 'r');
            $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

            $rawHeader = $file->fgetcsv();
            if (!$rawHeader) {
                $this->errorMessage = 'The CSV file is empty.';
                return;
            }

            $header = array_map(fn($col) => trim(strval($col)), $rawHeader);

            $importedCount = 0;
            $skippedCount = 0;
            $rows = [];

            while (!$file->eof()) {
                $row = $file->fgetcsv();
                if (!$row || count($row) !== count($header)) {
                    if ($row && !empty(array_filter($row))) {
                        $skippedCount++;
                    }
                    continue;
                }

                $data = array_combine($header, $row);

                if (empty($data['event_name']) || empty($data['event_date'])) {
                    $skippedCount++;
                    continue;
                }

                try {
                    $eventDate = Carbon::parse($data['event_date'])->format('Y-m-d');
                    $isBg = filter_var($data['is_background_image'] ?? false, FILTER_VALIDATE_BOOLEAN);

                    $rows[] = [
                        'event_name' => substr(trim($data['event_name']), 0, 100),
                        'event_date' => $eventDate,
                        'event_time' => !empty($data['event_time']) ? Carbon::parse($data['event_time'])->format('H:i:s') : null,
                        'event_type' => !empty($data['event_type']) ? trim($data['event_type']) : 'Personal',
                        'fields' => !empty($data['fields']) ? trim($data['fields']) : 'days,months,years',
                        'is_background_image' => $isBg,
                        'background' => !empty($data['background']) ? trim($data['background']) : 'from-purple-500 to-indigo-600',
                        'user_id' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $importedCount++;
                } catch (\Exception $e) {
                    Log::warning("UploadCSV row parse error: " . $e->getMessage());
                    $skippedCount++;
                }
            }

            if (!empty($rows)) {
                Event::insert($rows);
            }

            $this->message = "CSV file processed: {$importedCount} events imported, {$skippedCount} skipped.";
            $this->csvfile = null;
            session()->flash('status', 'Events imported.');
        } catch (ValidationException $e) {
            $this->errorMessage = 'Validation failed: ' . $e->getMessage();
        } catch (Exception $e) {
            $this->errorMessage = 'An error occurred during CSV processing: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.upload-c-s-v');
    }
}
