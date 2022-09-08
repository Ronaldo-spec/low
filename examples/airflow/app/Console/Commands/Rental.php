<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Rental extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'showRental';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan Table Rental dari SakilaDB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit =$this->ask('Berapa banyak data yang ingin ditampilkan?');
        $rental = DB::connection('sakiladb')->table('rental')->limit($limit)->get();
        dd($rental);
    }
}
