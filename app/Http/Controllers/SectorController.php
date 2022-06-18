<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\SectorsDataTable;
use App\Http\Requests\SectorsRequest;
use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends ApplicationController
{
    protected $hashId = true;
    /**
     * Display a listing of the resource.
     *
     * @param SectorsDataTable $dataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(SectorsDataTable $dataTable)
    {
        return $dataTable->render('standards.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SectorsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SectorsRequest $request)
    {
        $class = new Sector();

        return $this->createFlashRedirect($class, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function edit(Sector $sector)
    {
        return view('standards.edit', ['sector' => $sector]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SectorsRequest $request
     * @param Sector $sector
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Sector $sector)
    {
        if ($request->isMethod('put')) {

            $this->validate($request, [
                'name' => 'required|string|unique:sectors,name,'.$sector->id.'|max:255',
            ]);
        }

        return $this->saveFlashRedirect($sector, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Sector $sector
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Sector $sector)
    {
        return $this->destroyFlashRedirect($sector);
    }
}
