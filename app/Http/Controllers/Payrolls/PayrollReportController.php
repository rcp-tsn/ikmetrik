<?php

namespace App\Http\Controllers\Payrolls;

use App\Exports\PayrollAcceptExport;
use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\PayrollCalender;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Excel;

class PayrollReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = HashingSlug::decodeHash($id);
        $payroll =Payroll::find($id);
        if (!$payroll)
        {
            return back()->with('danger','Daha Sonra Tekrar Deneyiniz');
        }
        $calenders = PayrollCalender::where('payroll_id',$id)->get();

        return view('payrolls.reports.index',compact('calenders','id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function PayrollExport($id)
    {
        $id = HashingSlug::decodeHash($id);
        $payroll =Payroll::find($id);
        if (!$payroll)
        {
            return back()->with('danger','Daha Sonra Tekrar Deneyiniz');
        }
        $calenders = PayrollCalender::where('payroll_id',$id)->get();
        $date = $payroll->date;
        return Excel::download(new PayrollAcceptExport($calenders,$date), 'Bordro Raporlama' . ' - ' . date("Y-m-d") . '.xlsx');
    }
}
