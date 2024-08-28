<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Process;


class AdminConfigurationController extends Controller
{

      /**
     * Show the configuration form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve all the processes
        $processes = Process::all();

        // Return the Blade view with the processes data
        return view('admin.config', [
            'processes' => $processes
        ]);
    }
    /**
     * Store the configuration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'rows' => 'required|array',
            'rows.*.name' => 'required|string|max:255',
            'rows.*.email' => 'required|email|max:255',
            'rows.*.process_type' => 'required|string|in:orden_de_compra,confirmacion_despacho,pedido',
        ]);
    
        // Delete all existing records in the processes table
        Process::truncate();
    
        // Insert the new data
        foreach ($data['rows'] as $row) {
            Process::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'process_type' => $row['process_type'],
            ]);
        }
    
        return response()->json(['success' => true]);
    }
    
    
}
