<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the MySQL Database';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        if(!file_exists(database_path('backups'))){
            $this->info("Directory doesn't exists. Creating..");
            if(!mkdir(database_path('backups'))){
                return;
            }
            $this->info("Directory created.");
        }
        $filename = "backup-tme-". Carbon::now()->format('Y-m-d-H-i-s').'.sql';

        $this->info("Starting database dump...");

        $command = sprintf('mysqldump --user=%s --password=%s --host=%s %s > "%s"',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            database_path('backups'.DIRECTORY_SEPARATOR.$filename)
            );

        $this->info("Command to be executed: ");
        $this->info($command);

        $output = null;
        $status = null;

        exec($command, $output, $status);
        
        $status ? $this->error("Error Check output for details") : $this->info("Database backup Created: ". $filename);
        
        $this->line(implode(PHP_EOL, $output));
    }
}
