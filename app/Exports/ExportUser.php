<?php

namespace App\Exports;

use App\Models\Doctor;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportUser implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    //Export All Doctor Data
    public function collection()
    {
        return Doctor::all();
    }
}
