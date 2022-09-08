<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class inventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'showInven';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan Table Inventory dari SakilaDB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit =$this->ask('Berapa banyak data yang ingin ditampilkan?');
        $inventory = DB::connection('sakiladb')->table('inventory')->limit($limit)->get();
        dd($inventory);
    }
}
