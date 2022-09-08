<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class showAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'showAddress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan Table Address dari SakilaDB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit =$this->ask('Berapa banyak data yang ingin ditampilkan?');
        $address = DB::connection('sakilacoba')->table('address')->limit($limit)->get();
        dd($address);
    }
}
