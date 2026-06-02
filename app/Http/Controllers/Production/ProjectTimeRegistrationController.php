<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\ProjectTimeRegistration;
use App\Models\Rentman\Crew;
use App\Models\Rentman\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProjectTimeRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectTimeRegistration::with(['project', 'crewmember']);

        if ($request->has('project_id')) {
            $query->where('project_id', $request->get('project_id'));
        }

        return Inertia::render('Production/TimeRegistration/Index', [
            'registrations' => $query->get()->map(fn($r) => [
                'id'           => $r->id,
                'project_name' => $r->project?->full_displayname ?? $r->project_id,
                'crew_name'    => $r->crewmember?->displayname ?? $r->crewmember_id,
                'budget_type'  => $r->budget_type,
                'duration'     => $r->duration,
                'start'        => $r->start?->format('d-m-Y H:i'),
                'end'          => $r->end?->format('d-m-Y H:i'),
            ]),
        ]);
    }

    public function create()
    {
        $projects    = Project::orderBy('displayname', 'asc')->get();
        $crewmembers = Crew::orderBy('displayname', 'asc')->get();

        return Inertia::render('Production/TimeRegistration/Create', [
            'projects'    => $projects->map(fn($p) => ['id' => $p->id, 'label' => $p->displayname ?? $p->number]),
            'crewmembers' => $crewmembers->map(fn($c) => ['id' => $c->id, 'label' => $c->displayname]),
            'budgetTypes' => ['PM', 'Light', 'Sound', 'Rigging'],
            'defaultStart'=> now()->format('Y-m-d\TH:i'),
        ]);
    }

    public function store(Request $request)
    {
        $account = session('current_account');

        $validated = $request->validate([
            'project_id'    => ['required', Rule::exists('rm_projects', 'id')->where('account', $account)],
            'crewmember_id' => ['required', Rule::exists('rm_crew', 'id')->where('account', $account)],
            'budget_type'   => 'required|in:PM,Light,Sound,Rigging',
            'start'         => 'required|date',
            'end'           => 'nullable|date|after_or_equal:start',
            'duration'      => 'nullable|regex:/^\d+:[0-5][0-9]$/',
            'remarks'       => 'nullable|string',
        ]);

        $start           = Carbon::parse($validated['start']);
        $end             = $validated['end'] ? Carbon::parse($validated['end']) : null;
        $durationDecimal = null;

        if (!empty($validated['duration'])) {
            [$hours, $minutes] = explode(':', $validated['duration']);
            $totalMinutes      = ($hours * 60) + $minutes;
            $durationDecimal   = round($totalMinutes / 60, 2);
            if (!$end) {
                $end = $start->copy()->addMinutes($totalMinutes);
            }
        } elseif ($end) {
            $durationDecimal = round($start->diffInMinutes($end) / 60, 2);
        }

        $registration                = new ProjectTimeRegistration();
        $registration->project_id    = $validated['project_id'];
        $registration->crewmember_id = $validated['crewmember_id'];
        $registration->budget_type   = Str::lower($validated['budget_type']);
        $registration->start         = $start;
        $registration->end           = $end;
        $registration->duration      = $durationDecimal;
        $registration->remarks       = $validated['remarks'];
        $registration->created_by    = Auth::id();
        $registration->updated_by    = Auth::id();
        $registration->save();

        return redirect()->route('production.project.timeregistration.index')
            ->with('success', 'Tijdregistratie opgeslagen.');
    }

    public function show(ProjectTimeRegistration $timeregistration)
    {
        $timeregistration->load(['project', 'crewmember']);

        return Inertia::render('Production/TimeRegistration/Show', [
            'registration' => [
                'id'           => $timeregistration->id,
                'project_name' => $timeregistration->project?->full_displayname ?? $timeregistration->project_id,
                'crew_name'    => $timeregistration->crewmember?->displayname ?? $timeregistration->crewmember_id,
                'budget_type'  => $timeregistration->budget_type,
                'duration'     => $timeregistration->duration,
                'start'        => $timeregistration->start?->format('d-m-Y H:i'),
                'end'          => $timeregistration->end?->format('d-m-Y H:i'),
                'remarks'      => $timeregistration->remarks,
                'created_at'   => $timeregistration->created_at?->format('d-m-Y H:i:s'),
            ],
        ]);
    }

    public function edit(ProjectTimeRegistration $timeregistration)
    {
        $projects    = Project::orderBy('displayname', 'asc')->get();
        $crewmembers = Crew::orderBy('displayname', 'asc')->get();

        return Inertia::render('Production/TimeRegistration/Edit', [
            'registration' => [
                'id'            => $timeregistration->id,
                'project_id'    => $timeregistration->project_id,
                'crewmember_id' => $timeregistration->crewmember_id,
                'budget_type'   => ucfirst($timeregistration->budget_type),
                'start'         => $timeregistration->start?->format('Y-m-d\TH:i'),
                'end'           => $timeregistration->end?->format('Y-m-d\TH:i'),
                'duration'      => $timeregistration->duration,
                'remarks'       => $timeregistration->remarks,
            ],
            'projects'    => $projects->map(fn($p) => ['id' => $p->id, 'label' => $p->displayname ?? $p->number]),
            'crewmembers' => $crewmembers->map(fn($c) => ['id' => $c->id, 'label' => $c->displayname]),
            'budgetTypes' => ['PM', 'Light', 'Sound', 'Rigging'],
        ]);
    }

    public function update(Request $request, ProjectTimeRegistration $timeregistration)
    {
        $validated = $request->validate([
            'project_id'    => 'required|exists:rm_projects,id',
            'crewmember_id' => 'required|exists:rm_crew,id',
            'budget_type'   => 'required|in:PM,Light,Sound,Rigging',
            'start'         => 'nullable|date',
            'end'           => 'nullable|date|after_or_equal:start',
            'duration'      => 'nullable|numeric',
            'remarks'       => 'nullable|string',
        ]);

        if (!isset($validated['duration']) && isset($validated['start'], $validated['end'])) {
            $start                 = Carbon::parse($validated['start']);
            $end                   = Carbon::parse($validated['end']);
            $validated['duration'] = round($start->diffInMinutes($end) / 60, 2);
        }

        $timeregistration->updated_by = Auth::id();
        $timeregistration->update($validated);

        return redirect()->route('production.project.timeregistration.index')
            ->with('success', 'Tijdregistratie bijgewerkt.');
    }

    public function destroy(ProjectTimeRegistration $timeregistration)
    {
        $timeregistration->delete();

        return redirect()->route('production.project.timeregistration.index')
            ->with('success', 'Tijdregistratie verwijderd.');
    }
}
