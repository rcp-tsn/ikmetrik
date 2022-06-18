<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LeakExport implements FromView
{
    private $law_5510;
    private $law_27103;
    private $law_6111;
    private $law_17103;
    private $law_7252;
    private $laws;
    private $sgk_company;
    private $dates;
    private $win_6111;
    private $win_27103;
    private $win_17103;
    private $win_5510;
    private $win_7252;


    public function __construct($law_5510, $law_27103, $law_6111, $law_17103, $law_7252, $laws,$sgk_company,$dates,$win_27103,$win_17103,$win_7252,$win_6111,$win_5510)
    {
        $this->law_5510 = $law_5510;
        $this->law_27103 = $law_27103;
        $this->law_6111 = $law_6111;
        $this->law_17103 = $law_17103;
        $this->law_7252 = $law_7252;
        $this->laws = $laws;
        $this->sgk_company = $sgk_company;
        $this->win_6111 = $win_6111;
        $this->win_27103 = $win_27103;
        $this->win_17103 = $win_17103;
        $this->win_5510 = $win_5510;
        $this->win_7252 = $win_7252;
        $this->dates = $dates;

    }

    public function view(): View
    {
        return view('losts.lost_table',[
        'law_5510' => $this->law_5510,
        'law_27103' => $this->law_27103,
        'law_6111' => $this->law_6111,
        'law_7252' => $this->law_7252,
        'laws' => $this->laws,
        'sgk_company' => $this->sgk_company,
        'dates' => $this->dates,
        'win_6111' => $this->win_6111,
        'win_27103' => $this->win_27103,
        'win_17103' => $this->win_17103,
        'win_5510' => $this->win_5510,
        'win_7252' => $this->win_7252
        ]);
    }
}
