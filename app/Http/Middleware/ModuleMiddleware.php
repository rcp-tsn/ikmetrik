<?php

namespace App\Http\Middleware;

use App\Models\Module;
use Closure;

class ModuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $type)
    {
        $user = \Auth::user();
        $returnSuccess = true;

        $selectModule = Module::where('slug', $type)->first();
        $selectPackets = null;
        if ($selectModule) {
            $selectPackets = $user->company->packets()->whereHas('modules', function ($query) use ($selectModule) {
                $query->where('module_id', $selectModule->id);
            })->get();
        }

        if ($selectPackets === null || $selectPackets->count() === 0) {
            $returnSuccess = false;
        }

        if ($type === 'candidate_create') {
            $packetCandidate = $user->company->packets(1)->first();
            $candidates = $user->company->users()->withRole('candidate')->get();

            if (!$packetCandidate || ($packetCandidate->max_user_number <= $candidates->count())) {
                $returnSuccess = false;
            } else {
                $returnSuccess = true;
            }
        }

        if ($type === 'employee_create') {
            $packetEmployee = $user->company->packets(2)->first();
            $employees = $user->company->users()->withRole(['owner', 'human_resources', 'leader', 'employee'])->get();

            if (!$packetEmployee || ($packetEmployee->max_user_number <= $employees->count())) {
                $returnSuccess = false;
            } else {
                $returnSuccess = true;
            }
        }

        if (! $returnSuccess) {
            if ($request->ajax()) {
                return response(view('errors.402_modal'), 402);
            }
            return response(view('errors.402'), 402);
        }

        return $next($request);
    }
}
