<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class country extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'showCountry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan Table Country dari SakilaDB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit =$this->ask('Berapa banyak data yang ingin ditampilkan?');
        $country = DB::connection('sakiladb')->table('country')->limit($limit)->get();
        dd($country);
    }
}
