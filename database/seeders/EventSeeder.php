<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use SplFileObject;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $filepath = database_path('seeders/events.csv');

        if(!file_exists($filepath)){
            Log::error('Seeder file not found.');
            return;
        }

       $file = new SplFileObject($filepath, 'r');
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::READ_AHEAD);

        $header = $file->current();
        $file->next();

        $importedCount = 0;
        $skippedCount = 0;

        $rows = [];

        while (!$file->eof()) {
            $row = $file->current();

            if (empty($row) || count($row) !== count($header)) {
                $file->next(); 
                $skippedCount++;
                continue;
            }

            $row[2] = Carbon::parse($row[2])->format('Y-m-d');

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

        Event::insert($rows);

        Log::info("EventSeeder: Import complete!");
        Log::info("EventSeeder: Successfully imported: {$importedCount} records.");
        if ($skippedCount > 0) {
            Log::warning("EventSeeder: Skipped: {$skippedCount} records due to errors or malformed data.");
        }
    }
}
