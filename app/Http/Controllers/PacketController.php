<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\PacketsDataTable;
use App\Http\Requests\PacketsRequest;
use App\Models\Module;
use App\Models\Packet;
use App\Models\PacketCompany;
use App\Models\PacketInventory;
use Illuminate\Http\Request;

class PacketController extends ApplicationController
{

    protected $hashId = true;
    /**
     * Display a listing of the resource.
     *
     * @param PacketsDataTable $dataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(PacketsDataTable $dataTable)
    {
        return $dataTable->render('standards.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $packet = New Packet();
        $selectModules = [];
        $modules = Module::orderBy('title', 'ASC')->get()->pluck('title', 'id')->all();

        return view('standards.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PacketsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PacketsRequest $request)
    {
        $inputPacketData = $request->except(['modules']);
        $packet = Packet::create($inputPacketData);
        $packet->modules()->sync($request->modules);

        return $this->flashRedirect(route('packets.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Packet  $packet
     * @return \Illuminate\Http\Response
     */
    public function edit(Packet $packet)
    {
        $modules = Module::orderBy('title', 'ASC')->pluck('title', 'id')->all();
        $selectModules = $packet->modules()->orderBy('title', 'ASC')->get()->pluck('id')->all();

        return view('standards.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Packet $packet
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(PacketsRequest $request, Packet $packet)
    {
        PacketInventory::where('packet_id', $packet->id)->delete();
        if (isset($request->tests)) {
            foreach ($request->tests as $testId) {
                PacketInventory::create([
                    'packet_id' => $packet->id,
                    'inventorytable_type' => 'App\Model\Test',
                    'inventorytable_id' => $testId,
                ]);
            }
        }

        $inputPacketData = $request->except(['modules', 'tests']);

        $packet->update($inputPacketData);
        $packet->modules()->sync($request->modules);

        return $this->flashRedirect(route('packets.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Packet $packet
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Packet $packet)
    {
        $packetCompany = PacketCompany::where('packet_id', $packet->id)->get();
        if ($packetCompany->count()) {
            return $this->flashRedirect(null, 'danger', 'Paket kullanıldığı için silinemez.');
        }

        return $this->destroyFlashRedirect($packet);
    }
}
