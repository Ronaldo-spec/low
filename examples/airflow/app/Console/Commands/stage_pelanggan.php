<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class stage_pelanggan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stagePelanggan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stagepelanggan = DB::connection('sakilacoba')
            ->select("SELECT YEAR(ren.rental_date) as YEAR , negara.country , kota.city, negara.country_id, kota.city_id, count(customer_id) as jml_pelanggan
            from rental ren
            inner join inventory inven on ren.inventory_id = inven.inventory_id
            inner join store toko on inven.store_id = toko.store_id
            inner join address alamat on toko.address_id =alamat.address_id
            inner join city kota on alamat.city_id =kota.city_id
            inner join country negara on kota.country_id = negara.country_id
            group by year(ren.rental_date) , negara.country , kota.city, negara.country_id, kota.city_id ");

        foreach ($stagepelanggan as $value) {
            $year[] = $value->YEAR;
            $kota[] = $value->city;
            $negara[] = $value->country;
            $id_kota[] = $value->city_id;
            $id_negara[] = $value->country_id;
            $jumlah[] = $value->jml_pelanggan;
        }

        DB::connection('clickhouse')->table('stage_pelanggan')->where('city_id', $id_kota)->delete();

        foreach ($stagepelanggan as $key => $value) {
            $arrayInsert = array(
                'years' => $year[$key],
                'country' => $negara[$key],
                'city' => $kota[$key],
                'country_id' => $id_negara[$key],
                'city_id' => $id_kota[$key],
                'jml_pelanggan' => $jumlah[$key],
            );
            DB::connection('clickhouse')->table('stage_pelanggan')->insert($arrayInsert);
            $key++;
        }

    }
}
