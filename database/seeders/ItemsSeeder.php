<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $array = [
            '{"descripcion":"Harina de maiz 1 KG", "precio":"4.8"}',
            '{"descripcion":"Harina de trigo 1KG","precio":"4.6"}',
            '{"descripcion":"Aceite de girasol 1L","precio":"11"}',
            '{"descripcion":"Azucar refinada 1KG","precio":"6.2"}',
            '{"descripcion":"Arroz 1KG","precio":"1"}',
            '{"descripcion":"Espagueti 1KG","precio":"1.2"}',
            '{"descripcion":"Café amanecer 250G","precio":"2.5"}',
            '{"descripcion":"Leche en polvo semidescremada 1KG","precio":"22"}',
            '{"descripcion":"Detergente multiusos 500G","precio":"4.5"}',
            '{"descripcion":"Jabón de tocador","precio":"0.6"}',
              
        ];

        for ($i=0; $i < sizeof($array); $i++) {
            \DB::table("items")->insert(
                array(                      
                    'descripcion' => json_decode( $array[$i] )->descripcion,
                    'precio' => json_decode($array[$i])->precio,
                    'created_at' =>date('Y-m-d H:m:s'),
                    'updated_at' =>date('Y-m-d H:m:s')                 
                )   
            );
        }
    }
}
