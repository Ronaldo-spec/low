<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class dimtahun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chdimtahun';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan tabel dim_tahun dari ClickHouse';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit =$this->ask('Berapa banyak data yang ingin ditampilkan?');
        $jaya_dim_tahun = DB::connection('clickhouse')->table('dim_tahun')->limit($limit)->get();
        dd($jaya_dim_tahun);
    }
}
