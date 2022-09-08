<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class takeDimLokasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'takeDimLokasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memasukkan data ke table dim_lokasi';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dimlokasi = DB::connection('sakilacoba')
            ->select('SELECT n.country_id, n.country, k.city_id,k.city
        from country n,  city k
        where k.country_id = n.country_id
        order by k.country_id ASC');

        foreach ($dimlokasi as $value) {
            $id_negara[] = $value->country_id;
            $negara[] = $value->country;
            $id_kota[] = $value->city_id;
            $kota[] = $value->city;
        }

        $delete = DB::connection('clickhouse')
            ->select("SELECT id_lokasi, id_negara, negara, id_kota, kota
            from dim_lokasi"
            );

        foreach ($delete as $value) {
            $idlok[] = $value['id_lokasi'];
            $idn[] = $value['id_negara'];
            $n[] = $value['negara'];
            $idk[] = $value['id_kota'];
            $kota[] = $value['kota'];
        }

        if ($delete == null) {
            foreach ($dimlokasi as $key => $value) {
                $arrayInsert = array(
                    'id_lokasi' => $key + 1,
                    'id_negara' => $id_negara[$key],
                    'negara' => $negara[$key],
                    'id_kota' => $id_kota[$key],
                    'kota' => $kota[$key],
                );
                DB::connection('clickhouse')->table('dim_lokasi')->insert($arrayInsert);
                $key++;
            }
        } else {
            DB::connection('clickhouse')->table('dim_lokasi')->where('id_kota', $idk)->delete();
            foreach ($dimlokasi as $key => $value) {
                $arrayInsert = array(
                    'id_lokasi' => $key + 1,
                    'id_negara' => $id_negara[$key],
                    'negara' => $negara[$key],
                    'id_kota' => $id_kota[$key],
                    'kota' => $kota[$key],
                );
                DB::connection('clickhouse')->table('dim_lokasi')->insert($arrayInsert);
                $key++;
            }
        }

    }
}
