<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class city extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'showCity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan Table City dari SakilaDB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit =$this->ask('Berapa banyak data yang ingin ditampilkan?');
        $city = DB::connection('sakiladb')->table('city')->limit($limit)->get();
        dd($city);
    }
}
