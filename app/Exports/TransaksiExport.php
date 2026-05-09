<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransaksiExport implements FromView
{
    protected $orders;

    // Kita menerima data yang sudah difilter dari Controller
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function view(): View
    {
        // Beruntungnya, kita bisa pakai tampilan PDF tadi untuk Excel juga!
        return view('admin.report_pdf', [
            'orders' => $this->orders
        ]);
    }
}