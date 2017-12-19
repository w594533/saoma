<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TruncateTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all table datas';

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
        \DB::table('boxes')->delete();
        \DB::table('users')->delete();
        // \DB::table('users')->truncate();
        // \DB::table('boxes')->truncate();
        //移除目录文件
        // Storage::allDirectories('/public/upload');
        echo 'done.';
    }
}
