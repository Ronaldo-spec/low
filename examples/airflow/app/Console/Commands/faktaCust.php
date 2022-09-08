<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Tinderbox\ClickhouseBuilder\Query\JoinClause;
use Tinderbox\ClickhouseBuilder\Query\BaseBuilder;

class faktaCust extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faktaCust';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memasukkan data ke dalam table Fakta Pelanggan';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $factpelanggan = DB::connection('clickhouse')
            ->select('SELECT id_tahun, tahun from dim_tahun');

        $factpelanggan2 = DB::connection('clickhouse')
            ->select('SELECT DISTINCT id_lokasi, id_negara, negara, id_kota, kota from dim_lokasi');

        // $factpelanggan = DB::connection('clickhouse')
        //     ->select('SELECT id_tahun, tahun
        // from dim_tahun');

        // $factpelanggan2 = DB::connection('clickhouse')
        //     ->select('SELECT DISTINCT id_negara, negara, id_lokasi
        // from dim_lokasi');

        // foreach ($factpelanggan as $value) {
        //     $id_tahun[] = $value['id_tahun'];
        //     $tahun[] = $value['tahun'];
        // }

        // foreach ($factpelanggan2 as $value) {
        //     $id_negara[] = $value['id_negara'];
        // }

        $factpelanggan4 = DB::connection('clickhouse')
        ->select("SELECT t.id_tahun, l.id_lokasi, stage.jml_pelanggan
        from dim_tahun t
        inner join stage_pelanggan stage on t.tahun = stage.years
        inner join dim_lokasi l on stage.city_id = l.id_kota
        WHERE stage.country_id = l.id_negara and stage.city_id = l.id_kota
        ORDER BY t.id_tahun ASC , l.id_lokasi ASC, stage.jml_pelanggan ASC
        ");

        foreach ($factpelanggan4 as $value) {
            $tahun_id[] = $value['t.id_tahun'];
            $lokasi_id[] = $value['l.id_lokasi'];
            $jml[] = $value['stage.jml_pelanggan'];
        }

        $delete = DB::connection('clickhouse')
            ->select("SELECT id_tahun, id_lokasi, jml_pelanggan as jp
            from fakta_pelanggan"
            );

        foreach ($delete as $value) {
            $idt[] = $value['id_tahun'];
            $idlok[] = $value['id_lokasi'];
            $jp[] = $value['jp'];
        }

        if ($delete == null) {
            foreach ($factpelanggan4 as $key => $value) {
                $arrayInsert = array(
                    'id_tahun' => $tahun_id[$key],
                    'id_lokasi' => $lokasi_id[$key],
                    'jml_pelanggan' => $jml[$key],
                );
                DB::connection('clickhouse')->table('fakta_pelanggan')->insert($arrayInsert);
                $key++;
            }
        } else {
            DB::connection('clickhouse')->table('fakta_pelanggan')->where('id_lokasi', $idlok)->delete();
            foreach ($factpelanggan4 as $key => $value) {
                $arrayInsert = array(
                    'id_tahun' => $tahun_id[$key],
                    'id_lokasi' => $lokasi_id[$key],
                    'jml_pelanggan' => $jml[$key],
                );
                DB::connection('clickhouse')->table('fakta_pelanggan')->insert($arrayInsert);
                $key++;
            }
        }
        

        //     
        //
        //DB::connection('clickhouse')->table('fakta_pelanggan')->where('id_lokasi', Not Null)->delete();

        
    }

}

// --------------------------------- TIDAK DIGUNAKAN -------------------------------------

// else if (DB::connection('clickhouse')->table('fakta_pelanggan') != null) {
//
// }

// foreach ($productdiscountDelete as $cat) {
//     $productsDelete = VlDiscountProduct::findFirst($cat->$discountUpdate->autokey);
//     if ($deleteproductDiscount != false) {
//         if ($productsDelete->delete() == false) {
//             $deleteErrors++;
//             $devMessage = array();
//             foreach ($deleteproductDiscount->getMessages() as $key) {
//                 $devMessage[] = $key->getMessage();
//             }
//         }
//     }
// }

// $jmlCust = DB::connection('sakilacoba')
// ->select('SELECT r.customer_id, YEAR(rental_date) as year
// from rental r');

// foreach ($jmlCust as $value) {
//     $custID[]=$value->customer_id;
//     $year[]=$value->year;
// }

// $emptyArray =array();
// $emptyArray = nput::get($factpelanggan);
// $emptyArray = input::get($jmlCust);
// $value = new Values;

// foreach ($emptyArray as $key => $value) {
//     $value->id_tahun = $id_tahun[$key];
//     $value->id_lokasi =$id_lokasi[$key];
//     $value->tahun = $tahun[$key];
//     $value->customer_id = $custID[$key];
//     $value->year =  $year[$key];
//     $value->save();
// }
