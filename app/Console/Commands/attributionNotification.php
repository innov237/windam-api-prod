<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class attributionNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Notification:AttRun';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Commande d\'envoi des notification lors d\'une attribution';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       
     
    $this->info('Hourly Update has been send successfully');
    }
}
