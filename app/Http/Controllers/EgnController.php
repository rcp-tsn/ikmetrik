<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\EgnsDataTable;
use App\Http\Requests\EgnsRequest;
use App\Models\Egn;
use Illuminate\Http\Request;

class EgnController extends ApplicationController
{
    protected $hashId = true;
    /**
     * Display a listing of the resource.
     *
     * @param EgnsDataTable $dataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(EgnsDataTable $dataTable)
    {
        return $dataTable->render('standards.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EgnsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(EgnsRequest $request)
    {
        $class = new Egn();

        return $this->createFlashRedirect($class, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Egn  $egn
     * @return \Illuminate\Http\Response
     */
    public function edit(Egn $egn)
    {
        return view('standards.edit', ['egn' => $egn]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EgnsRequest $request
     * @param Egn $egn
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Egn $egn)
    {
        if ($request->isMethod('put')) {

            $this->validate($request, [
                'name' => 'required|string|unique:egns,name,'.$egn->id.'|max:255',
            ]);
        }

        return $this->saveFlashRedirect($egn, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Egn $egn
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Egn $egn)
    {
        return $this->destroyFlashRedirect($egn);
    }
}
