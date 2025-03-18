<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportStudentsCommand extends Command
{
    protected $signature = 'students:import {csvPath}';
    protected $description = 'Import students from a CSV file and overwrite existing records';

    public function handle()
    {
        $csvPath = $this->argument('csvPath');
        $csvFile = fopen($csvPath, 'r');

        // Step 1: Delete all existing records in the students table
        DB::table('students')->truncate(); // This deletes all records but keeps the table structure intact

        // Step 2: Import new records from the CSV file
        while (($row = fgetcsv($csvFile)) !== FALSE) {
            // Skip empty rows
            if (empty($row[0])) {
                continue;
            }

            // Insert new student record
            DB::table('students')->insert([
                'email' => $row[0],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        fclose($csvFile);

        $this->info('Students list has been successfully updated!');
    }
}

