<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class takeTahunRental extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'takeTahunRental';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengambil data tahun dari table Rental';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tahunrentalsakila = DB::connection('sakilacoba')
            ->table('rental')
            ->select(DB::raw('YEAR(rental_date) as YEAR'))
            ->distinct()
            ->get();

        foreach ($tahunrentalsakila as $value) {
            $tahun[] = $value->YEAR;
        }

        $delete = DB::connection('clickhouse')
            ->select("SELECT id_tahun, tahun
        from dim_tahun"
            );

        foreach ($delete as $value) {
            $idt[] = $value['id_tahun'];
            $t[] = $value['tahun'];
        }

        if ($delete == null) {
            foreach ($tahunrentalsakila as $key => $value) {
                $arrayInsert = array(
                    'id_tahun' => $key + 1,
                    'tahun' => $tahun[$key],
                );
                DB::connection('clickhouse')->table('dim_tahun')->insert($arrayInsert);
                $key++;
            }
        } else {
            DB::connection('clickhouse')->table('dim_tahun')->where('id_tahun', $idt)->delete();
            foreach ($tahunrentalsakila as $key => $value) {
                $arrayInsert = array(
                    'id_tahun' => $key + 1,
                    'tahun' => $tahun[$key],
                );
                DB::connection('clickhouse')->table('dim_tahun')->insert($arrayInsert);
                $key++;
            }
        }

        

    }

}
