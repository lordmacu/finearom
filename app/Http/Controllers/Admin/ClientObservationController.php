<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientObservation;
use Illuminate\Http\Request;

class ClientObservationController extends Controller
{
    public function index(Client $client)
    {
        $client->load('observation');
        $observation = $client->observation;
        return view('admin.client_observation.index', compact('client', 'observation'));
    }

    public function create(Client $client)
    {
        $observation = $client->observation ?? new ClientObservation();
        return view('admin.client_observation.create', compact('client', 'observation'));
    }

    public function store(Request $request, Client $client)
    {
        $request->validate([
            'requires_physical_invoice' => 'nullable|boolean',
            'packaging_unit' => 'nullable|integer',
            'requires_appointment' => 'nullable|boolean',
            'is_in_free_zone' => 'nullable|boolean',
            'billing_closure_date' => 'nullable|string',
            'reteica' => 'nullable|integer',
            'retefuente' => 'nullable|integer',
            'reteiva' => 'nullable|integer',
            'additional_observations' => 'nullable|string',
        ]);

        $client->observation()->updateOrCreate(
            ['client_id' => $client->id],
            [
                'requires_physical_invoice' => $request->requires_physical_invoice ?? false,
                'packaging_unit' => $request->packaging_unit,
                'requires_appointment' => $request->requires_appointment ?? false,
                'is_in_free_zone' => $request->is_in_free_zone ?? false,
                'billing_closure_date' => $request->billing_closure_date,
                'reteica' => $request->reteica ?? 0,
                'retefuente' => $request->retefuente ?? 0,
                'reteiva' => $request->reteiva ?? 0,
                'additional_observations' => $request->additional_observations,
            ]
        );

        return redirect()->route('admin.client.index', $client->id)
            ->with('message', 'Observation added/updated successfully.');
    }

    public function edit(Client $client)
    {
        $observation = $client->observation;
        return view('admin.client_observation.create', compact('client', 'observation'));
    }

    public function update(Request $request, Client $client, ClientObservation $observation)
    {
        $request->validate([
            'requires_physical_invoice' => 'nullable|boolean',
            'packaging_unit' => 'nullable|integer',
            'requires_appointment' => 'nullable|boolean',
            'is_in_free_zone' => 'nullable|boolean',
            'billing_closure_date' => 'nullable|string',
            'reteica' => 'nullable|integer',
            'retefuente' => 'nullable|integer',
            'reteiva' => 'nullable|integer',
            'additional_observations' => 'nullable|string',
        ]);

        $observation->update($request->all());

        return redirect()->route('admin.client.index', $client->id)
            ->with('message', 'Observation updated successfully.');
    }

    public function destroy(Client $client, ClientObservation $observation)
    {
        $observation->delete();

        return redirect()->route('admin.client.index', $client->id)
            ->with('message', 'Observation deleted successfully.');
    }
}
