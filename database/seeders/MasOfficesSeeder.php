<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasOfficesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mas_offices')->insert([
            ['id' => 1, 'name' => 'Trongsa Extension Office', 'address' => 'Above Bumthang-Trongsa Highway', 'mas_dzongkhag_id' => 17, 'created_by' => 1],
            ['id' => 2, 'name' => 'Langthel, Trongsa Extension Office', 'address' => 'Below Langthel Lower Secondary School', 'mas_dzongkhag_id' => 17, 'created_by' => 1],
            ['id' => 3, 'name' => 'Sarpang Extension Office', 'address' => 'Sachemthang Town - Milan Karki Building', 'mas_dzongkhag_id' => 13, 'created_by' => 1],
            ['id' => 4, 'name' => 'Zhemgang Extension Office', 'address' => 'KD Building nearby BOB Bank', 'mas_dzongkhag_id' => 20, 'created_by' => 1],
            ['id' => 5, 'name' => 'Panbang, Zhemgang Extension Office', 'address' => 'Panbang town', 'mas_dzongkhag_id' => 20, 'created_by' => 1],
            ['id' => 6, 'name' => 'Gyelpoishing Extension Office', 'address' => 'Next to Dawa Yoezer Karoke', 'mas_dzongkhag_id' => 7, 'created_by' => 1],
            ['id' => 7, 'name' => 'Autsho, Lhuentse Extension Office', 'address' => 'Next to Bakery', 'mas_dzongkhag_id' => 6, 'created_by' => 1],
            ['id' => 8, 'name' => 'Lhuentse Extension Office', 'address' => 'Aum Chimi\'s Building, Above RSTA Office', 'mas_dzongkhag_id' => 6, 'created_by' => 1],
            ['id' => 9, 'name' => 'Airport Extension Office', 'address' => 'Paro International Airport - Arrival Terminal', 'mas_dzongkhag_id' => 8, 'created_by' => 1],
            ['id' => 10, 'name' => 'Haa Extension Office', 'address' => 'Near parking, Lower Market', 'mas_dzongkhag_id' => 5, 'created_by' => 1],
            ['id' => 11, 'name' => 'Tshimasham Extension Office', 'address' => 'Tshimasham Town', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 12, 'name' => 'Gedu Extension Office', 'address' => 'Near Gedu College of Business Studies', 'mas_dzongkhag_id' => 2, 'created_by' => 1],
            ['id' => 13, 'name' => 'Kalikhola Extension Office', 'address' => 'Kalikhola Town', 'mas_dzongkhag_id' => 13, 'created_by' => 1],
            ['id' => 14, 'name' => 'Pema Gatshel Extension Office', 'address' => 'Zero point ,Karsel building', 'mas_dzongkhag_id' => 9, 'created_by' => 1],
            ['id' => 15, 'name' => 'Daifam Extension Office', 'address' => 'Parallel to BOB office (Town)', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            ['id' => 16, 'name' => 'Nganglam Extension Office', 'address' => 'Opposite to Nganglam Chorten', 'mas_dzongkhag_id' => 9, 'created_by' => 1],
            ['id' => 17, 'name' => 'Bhangtar Extension Office', 'address' => 'Main Town (Red building)', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            ['id' => 18, 'name' => 'Dorokha Extension Office', 'address' => 'CB Rai Building, Dorokha Town', 'mas_dzongkhag_id' => 12, 'created_by' => 1],
            ['id' => 19, 'name' => 'Sibsoo Extension Office', 'address' => 'Sibsoo Belbotey - Next to BDBL office', 'mas_dzongkhag_id' => 12, 'created_by' => 1],
            ['id' => 20, 'name' => 'Gomtu Extension Office', 'address' => 'Next to Zangdropelri Gomtu', 'mas_dzongkhag_id' => 12, 'created_by' => 1],
            ['id' => 21, 'name' => 'Babesa Extension Office', 'address' => 'Bap Namgay Building', 'mas_dzongkhag_id' => 14, 'created_by' => 1],
            ['id' => 22, 'name' => 'Changzamtog Extension Office', 'address' => 'Near Cypress tree', 'mas_dzongkhag_id' => 14, 'created_by' => 1],
            ['id' => 23, 'name' => 'Taba Extension Office', 'address' => 'Near BOB ATM', 'mas_dzongkhag_id' => 14, 'created_by' => 1],
            ['id' => 24, 'name' => 'Tashiyangtse Extension Office', 'address' => 'Yangtse Town (Below BDBL office)', 'mas_dzongkhag_id' => 16, 'created_by' => 1],
            ['id' => 25, 'name' => 'Wamrong Extension Office', 'address' => 'Wamrong Town (Next to RICBL office)', 'mas_dzongkhag_id' => 15, 'created_by' => 1],
            ['id' => 26, 'name' => 'Doksum, Trashiyangtse Extension Office', 'address' => 'Above Doksum PS', 'mas_dzongkhag_id' => 16, 'created_by' => 1],
            ['id' => 27, 'name' => 'Kanglung Extension Office', 'address' => 'Kanglung Lower Market (2nd Floor of Mini Mart building)', 'mas_dzongkhag_id' => 15, 'created_by' => 1],
            ['id' => 28, 'name' => 'Dagapela Extension Office', 'address' => 'Dagapela town, Nearby BOBL', 'mas_dzongkhag_id' => 3, 'created_by' => 1],
            ['id' => 29, 'name' => 'Dagana Extension Office', 'address' => 'Dagana Main Town', 'mas_dzongkhag_id' => 3, 'created_by' => 1],
            ['id' => 30, 'name' => 'Punakha Extension Office', 'address' => 'Current RICBL Building', 'mas_dzongkhag_id' => 10, 'created_by' => 1],
            ['id' => 31, 'name' => 'Bumthang Regional Office', 'address' => 'Near Roundabout, Chamkhar Town', 'mas_dzongkhag_id' => 1, 'created_by' => 1],
            ['id' => 32, 'name' => 'Gelephu Regional Office', 'address' => 'Titanic Building, 1st Floor, Jangchub Lam', 'mas_dzongkhag_id' => 13, 'created_by' => 1],
            ['id' => 33, 'name' => 'Mongar Regional Office', 'address' => 'Between Tbank and Army Retired Office', 'mas_dzongkhag_id' => 7, 'created_by' => 1],
            ['id' => 34, 'name' => 'Paro Regional Office', 'address' => 'Town (Opposite to children park)', 'mas_dzongkhag_id' => 8, 'created_by' => 1],
            ['id' => 35, 'name' => 'Phuentsholing Regional Office', 'address' => 'Tashi Complex Building Near Zangdopelri (Phuentsholing Town)', 'mas_dzongkhag_id' => 12, 'created_by' => 1],
            ['id' => 36, 'name' => 'Samdrup Jongkhar Regional Office', 'address' => 'Druk Mountain Building', 'mas_dzongkhag_id' => 11, 'created_by' => 1],
            ['id' => 37, 'name' => 'Samtse Regional Office', 'address' => 'Phuntsho Building', 'mas_dzongkhag_id' => 12, 'created_by' => 1],
            ['id' => 38, 'name' => 'Thimphu Regional Office', 'address' => 'Opposite to Taj Tashi and next to T Bank', 'mas_dzongkhag_id' => 14, 'created_by' => 1],
            ['id' => 39, 'name' => 'Trashigang Regional Office', 'address' => 'Lepcha Hotel (Main Town)', 'mas_dzongkhag_id' => 15, 'created_by' => 1],
            ['id' => 40, 'name' => 'Tsirang Regional Office', 'address' => 'Dinanath Audhikari building, near to Old Central hotel', 'mas_dzongkhag_id' => 18, 'created_by' => 1],
            ['id' => 41, 'name' => 'Wangdue Regional Office', 'address' => 'Bajo Town ( Above T Bank Limited) near Taxi Parking', 'mas_dzongkhag_id' => 19, 'created_by' => 1],
            ['id' => 42, 'name' => 'Rangjung Extension Office', 'address' => 'Rangjung Town (On the way to Rangjung Monetary)', 'mas_dzongkhag_id' => 15, 'created_by' => 1],
        ]);
    }
}
