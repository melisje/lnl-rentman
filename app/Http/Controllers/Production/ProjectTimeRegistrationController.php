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

class ProjectTimeRegistrationController extends Controller
{
    public function index(Request $request)
    {
        // Start de query op basis van het account van de ingelogde gebruiker
        $query = ProjectTimeRegistration::with(['project', 'crewmember']);

        // Filter 1: Als er een project_id is meegegeven in de URL, filter daarop
        if ($request->has('project_id'))
        {
            $query->where('project_id', $request->get('project_id'));
        }

        // Filter 2: Als er een budget_type is meegegeven, filter daarop
        // if ($request->has('budget_type'))
        // {
        //     $query->where('budget_type', $request->get('budget_type'));
        // }

        // Haal de gefilterde resultaten op
        $registrations = $query->get();

        return view('production.project.timeregistration.index', compact('registrations'));
    }

    public function create()
    {
        // Haal alle projecten op, gesorteerd op displayname
        $projects = Project::orderBy('displayname','asc')->get();

        // Optioneel: doe hetzelfde voor crewmembers als je die ook als lijst wilt
        $crewmembers = Crew::orderBy('displayname','asc')->get();

        return view('production.project.timeregistration.create', compact('projects', 'crewmembers'));
    }

    public function store(Request $request)
    {
        $account = session('current_account');

        // 1. Validatie (start is verplicht, duration is nu een string/tijdformaat)
        $validated = $request->validate([
            'project_id'    => ['required', Rule::exists('rm_projects', 'id')->where('account', $account)],
            'crewmember_id' => ['required', Rule::exists('rm_crew', 'id')->where('account', $account)],
            'budget_type'   => 'required|in:PM,Light,Sound,Rigging',
            'start'         => 'required|date', // Nu altijd VERPLICHT
            'end'           => 'nullable|date|after_or_equal:start',
            'duration'      => 'nullable|regex:/^\d+:[0-5][0-9]$/', // Valideert hh:mm (bijv. 02:30 or 120:45)
            'remarks'       => 'nullable|string',
        ]);

        // Maak een Carbon instantie van de starttijd
        $start = Carbon::parse($validated['start']);
        $end = $validated['end'] ? Carbon::parse($validated['end']) : null;
        $durationDecimal = null;

        // 2. Logica voor de hh:mm duration en eindtijd berekening
        if (!empty($validated['duration'])) {
            // Splits hh:mm op
            list($hours, $minutes) = explode(':', $validated['duration']);

            // Bereken totale minuten en decimale uren (voor opslag in database, bijv. 2:30 wordt 2.5)
            $totalMinutes = ($hours * 60) + $minutes;
            $durationDecimal = round($totalMinutes / 60, 2);

            // Als er GEEN eindtijd is ingevuld, bereken deze op basis van start + duration
            if (!$end) {
                $end = $start->copy()->addMinutes($totalMinutes);
            }
        } elseif ($end) {
            // Als duration LEEG is, maar end is WEL ingevuld: bereken de duration automatisch
            $durationDecimal = round($start->diffInMinutes($end) / 60, 2);
        }

        // 3. Opslaan in de database
        $registration = new ProjectTimeRegistration();
        $registration->project_id = $validated['project_id'];
        $registration->crewmember_id = $validated['crewmember_id'];
        $registration->budget_type = Str::lower($validated['budget_type']); // Sla op als lowercase (bijv. 'light' in plaats van 'Light')
        $registration->start = $start;
        $registration->end = $end; // Dit is nu ingevuld (indien berekend)
        $registration->duration = $durationDecimal; // Slaat op als decimaal getal (bijv. 2.50)
        $registration->remarks = $validated['remarks'];
        $registration->created_by = Auth::user()->id; // Optioneel: wie heeft deze registratie gemaakt?
        $registration->updated_by = Auth::user()->id; // Optioneel: wie heeft deze registratie bijgewerkt?
        $registration->save();

        return redirect()->route('production.project.timeregistration.index')->with('success', 'Tijdregistratie succesvol opgeslagen.');
    }

    public function show(Request $request, ProjectTimeRegistration $timeregistration)
    {
        return view('production.project.timeregistration.show', compact('timeregistration'));
    }

    public function edit(ProjectTimeRegistration $timeregistration)
    {
        // Haal alle projecten op, gesorteerd op displayname
        $projects = Project::orderBy('displayname','asc')->get();

        // Optioneel: doe hetzelfde voor crewmembers als je die ook als lijst wilt
        $crewmembers = Crew::orderBy('displayname','asc')->get();

        return view('production.project.timeregistration.edit', compact('timeregistration', 'projects', 'crewmembers'));
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
            $start = Carbon::parse($validated['start']);
            $end = Carbon::parse($validated['end']);
            $validated['duration'] = round($start->diffInMinutes($end) / 60, 2);
        }

        $timeregistration->updated_by = Auth::user()->id; // Optioneel: wie heeft deze registratie bijgewerkt?

        $timeregistration->update($validated);

        return redirect()->route('production.project.timeregistration.index')->with('success', 'Tijdregistratie succesvol bijgewerkt.');
    }

    public function destroy(ProjectTimeRegistration $timeregistration)
    {
        $timeregistration->delete(); // Verwijder de tijdregistratie
        return redirect()->route('production.project.timeregistration.index')->with('success', 'Tijdregistratie verwijderd.');
    }
}
