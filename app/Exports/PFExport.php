<?php

namespace App\Exports;

use App\Models\finalPaySlip;
use Maatwebsite\Excel\Concerns\FromCollection;

class PFExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return finalPaySlip::all();
    }
}
