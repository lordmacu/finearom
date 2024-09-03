<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use App\Models\Client;
use Illuminate\Http\Request;

use App\Exports\BranchOfficesExport;
use Maatwebsite\Excel\Facades\Excel;

class BranchOfficeController extends Controller
{
    public function index($clientId)
    {
        $client = Client::findOrFail($clientId);
        $branchOffices = BranchOffice::where('client_id', $clientId)->get();
        return view('admin.branch_offices.index', compact('client', 'branchOffices'));
    }


    public function exportExcel()
    {
        return Excel::download(new BranchOfficesExport, 'branch_offices.xlsx');
    }

    public function create($clientId)
    {
        $client = Client::findOrFail($clientId);
        return view('admin.branch_offices.create', compact('client'));
    }

    public function store(Request $request, $clientId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nit' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'billing_contact' => 'required|string|max:255',
            'delivery_address' => 'required|string|max:255',
            'delivery_city' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'shipping_observations' => 'nullable|string',
            'general_observations' => 'nullable|string',
        ]);

        BranchOffice::create([
            'name' => $request->name,
            'nit' => $request->nit,
            'client_id' => $clientId,
            'contact' => $request->contact,
            'billing_contact' => $request->billing_contact,
            'delivery_address' => $request->delivery_address,
            'delivery_city' => $request->delivery_city,
            'billing_address' => $request->billing_address,
            'billing_city' => $request->billing_city,
            'phone' => $request->phone,
            'shipping_observations' => $request->shipping_observations,
            'general_observations' => $request->general_observations,
        ]);

        return redirect()->route('admin.branch_offices.index', $clientId)
            ->with('message', 'Branch Office created successfully.');
    }

    public function edit($clientId, $id)
    {
        $client = Client::findOrFail($clientId);
        $branchOffice = BranchOffice::findOrFail($id);
        return view('admin.branch_offices.edit', compact('client', 'branchOffice'));
    }

    public function update(Request $request, $clientId, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nit' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'billing_contact' => 'required|string|max:255',
            'delivery_address' => 'required|string|max:255',
            'delivery_city' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'shipping_observations' => 'nullable|string',
            'general_observations' => 'nullable|string',
        ]);

        $branchOffice = BranchOffice::findOrFail($id);
        $branchOffice->update($request->all());

        return redirect()->route('admin.branch_offices.index', $clientId)
            ->with('message', 'Branch Office updated successfully.');
    }

    public function destroy($clientId, $id)
    {
        $branchOffice = BranchOffice::findOrFail($id);
        $branchOffice->delete();

        return redirect()->route('admin.branch_offices.index', $clientId)
            ->with('message', 'Branch Office deleted successfully.');
    }
}
