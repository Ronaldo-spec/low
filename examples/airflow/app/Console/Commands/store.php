<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class store extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'showStore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan Table Store dari SakilaDB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit =$this->ask('Berapa banyak data yang ingin ditampilkan?');
        $store = DB::connection('sakiladb')->table('store')->limit($limit)->get();
        dd($store);
    }
}
