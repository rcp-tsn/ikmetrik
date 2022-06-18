<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\IncentiveReportsDataTable;
use Illuminate\Http\Request;

class IncentiveReportController extends ApplicationController
{
    /**
     * Display a listing of the resource.
     *
     * @param IncentiveReportsDataTable $dataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(IncentiveReportsDataTable $dataTable)
    {
        return $dataTable->render('standards.index');
    }
}
