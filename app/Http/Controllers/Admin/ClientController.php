<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:client list', ['only' => ['index']]);
        $this->middleware('can:client create', ['only' => ['create', 'store']]);
        $this->middleware('can:client edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:client delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $clients_query = Client::query();
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $clients_query->where('client_name', 'like', '%' . $search . '%')
                ->orWhere('nit', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        if ($sort_by = $request->query('sort_by')) {
            $sort_order = $request->query('sort_order', 'asc');
            $clients_query->orderBy($sort_by, $sort_order);
        } else {
            $clients_query->latest();
        }
    
        $clients = $clients_query->paginate(config('admin.paginate.per_page'))
            ->appends($request->except('page'))
            ->onEachSide(config('admin.paginate.each_side'));
    
        return view('admin.client.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.client.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_type' => 'required|in:pareto,balance',
            'email' => 'required|email|unique:clients,email|unique:users,email',
            'billing_closure' => 'required|string|max:255',
            'commercial_conditions' => 'nullable|string',
            'proforma_invoice' => 'required|boolean',
            'payment_method' => 'required|in:1,2',
            'payment_day' => 'required|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $client = Client::create([
            'client_name' => $request->client_name,
            'client_type' => $request->client_type,
            'payment_type' => $request->payment_method,
            'email' => $request->email,
            'billing_closure' => $request->billing_closure,
            'commercial_conditions' => $request->commercial_conditions,
            'proforma_invoice' => $request->proforma_invoice,
            'payment_method' => $request->payment_method,
            'payment_day' => $request->payment_day,
            'status' => $request->status,
        ]);

        $user = User::create([
            'name' => $request->client_name,
            'email' => $request->email,
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('order-creator');

        return redirect()->route('admin.client.index')
            ->with('message', 'Cliente y usuario creados exitosamente.');
    }

    public function edit(Client $client)
    {
        return view('admin.client.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_type' => 'required|in:pareto,balance',
            'email' => 'required|string|email|max:255|unique:clients,email,' . $client->id,
            'billing_closure' => 'required|string|max:255',
            'commercial_conditions' => 'nullable|string',
            'proforma_invoice' => 'required|boolean',
            'payment_method' => 'required|in:1,2',
            'payment_day' => 'required|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $client->update($request->all());

        return redirect()->route('admin.client.index')
            ->with('message', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('admin.client.index')
            ->with('message', 'Cliente eliminado exitosamente.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'clients_excel' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new ClientsImport, $request->file('clients_excel'));

        return redirect()->route('admin.client.index')->with('message', 'Clientes importados exitosamente.');
    }
}
