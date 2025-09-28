<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StockCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('tr_TR');

        $categories = [
            [
                'name' => 'Tıbbi Sarf Malzemeleri',
                'description' => 'Eldiven, maske, steril örtü gibi tıbbi sarf malzemeleri',
                'is_medical_supplies' => true
            ],
            [
                'name' => 'Diş Dolgu Malzemeleri',
                'description' => 'Kompozit, amalgam, bonding gibi dolgu malzemeleri',
                'is_medical_supplies' => true
            ],
            [
                'name' => 'İmplant ve Protez',
                'description' => 'İmplantlar, protezler ve ilgili malzemeler',
                'is_medical_supplies' => true
            ],
            [
                'name' => 'Anestezi ve Sedasyon',
                'description' => 'Lokal anestezi ilaçları ve sedasyon malzemeleri',
                'is_medical_supplies' => true
            ],
            [
                'name' => 'Endodonti Malzemeleri',
                'description' => 'Kanal tedavisi için gerekli malzemeler',
                'is_medical_supplies' => true
            ],
            [
                'name' => 'Periodonti Malzemeleri',
                'description' => 'Diş eti tedavisi malzemeleri',
                'is_medical_supplies' => true
            ],
            [
                'name' => 'Ortodonti Malzemeleri',
                'description' => 'Braket, tel, aparey gibi ortodonti malzemeleri',
                'is_medical_supplies' => true
            ],
            [
                'name' => 'Röntgen Malzemeleri',
                'description' => 'Röntgen filmleri ve ilgili malzemeler',
                'is_medical_supplies' => true
            ],
            [
                'name' => 'Sterilizasyon',
                'description' => 'Oto klav, sterilizasyon poşetleri',
                'is_medical_supplies' => true
            ],
            [
                'name' => 'Ofis Malzemeleri',
                'description' => 'Kalem, kağıt, dosya gibi ofis malzemeleri',
                'is_medical_supplies' => false
            ],
            [
                'name' => 'Temizlik Malzemeleri',
                'description' => 'Temizlik ürünleri ve malzemeleri',
                'is_medical_supplies' => false
            ],
            [
                'name' => 'Teknik Ekipman',
                'description' => 'Diş üniteleri, ışıklar, aletler',
                'is_medical_supplies' => false
            ],
        ];

        foreach ($categories as $category) {
            DB::table('stock_categories')->insert([
                'name' => $category['name'],
                'description' => $category['description'],
                'is_medical_supplies' => $category['is_medical_supplies'],
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}