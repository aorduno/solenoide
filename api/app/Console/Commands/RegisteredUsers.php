<?php

namespace App\Console\Commands;

use App\Processors\TransactionUploadProcessor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RegisteredUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test command';

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
     * @return mixed
     */
    public function handle()
    {
        $usersCount = DB::table('users')->count();
        $files = Storage::disk('local')->files('transactions');

        $maxUploadSize = ini_get('upload_max_filesize');
        $maxPostSize = ini_get('post_max_size');

        $workers = array();
        foreach ($files as $file) {
            $workers[] = new TransactionUploadProcessor($files);
        }

        foreach ($workers as $worker) {
            $worker->run();
        }

        $this->line('Hello good sir! you have ' . $usersCount . ' registered in your site and ' . count($files) . ' files');
        $this->line('maxUploadSize' . $maxUploadSize);
        $this->line('maxPostSize' . $maxPostSize);
    }
}
