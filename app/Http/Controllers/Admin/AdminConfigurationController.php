<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\Models\BranchOffice;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Process;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PurchaseOrderImport;
use Illuminate\Support\Facades\Storage;

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
    

    public function uploadConfig(Request $request)
    {
 // Crear una instancia del importador
 $importer = new ProductImport();

 // Ejecutar la importación
 Excel::import($importer, $request->file('file'));

 // Obtener los errores después de la importación
 $errors = $importer->getErrors();

 // Si hay errores, generar un CSV
 if (!empty($errors)) {
     // Nombre del archivo CSV
     $filename = 'import_errors_' . date('Ymd_His') . '.csv';

     // Crear el archivo CSV
     $csvContent = "product,nit_cod,nit,error\n";

     foreach ($errors as $error) {
         $csvContent .= trim($error['product']) . "," . trim($error['nit_cod'])  . "," . trim($error['nit']) . "," . trim($error['error']). "\n";
     } 

     echo $csvContent;
    return response()->json([
        'status' => 'success',
        'message' => 'Import completed successfully.',
    ]);
    }
    
}
}
