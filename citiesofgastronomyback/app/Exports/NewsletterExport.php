<?php

namespace App\Exports;

use App\Models\Newletter;
use Maatwebsite\Excel\Concerns\FromCollection;

class NewsletterExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($data_startdate, $data_enddate)
    {
        $this->data_startdate = $data_startdate;
        $this->data_enddate = $data_enddate;
    }


    public function collection()
    {
        return (New Newletter())->datesearch($this->data_startdate, $this->data_enddate);
    }
}
