<?php

namespace App\Http\Controllers;

use App\Models\BranchOffice;
use App\Models\Client;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class PurchaseOrderAuthController extends Controller
{



    public function showEmailForm()
    {
        return view('auth.purchase_order_email');
    }

    public function sendCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $code = rand(1000, 9999);
        session(['verification_code' => $code, 'email' => $request->email]);
    
        Mail::raw("Your verification code is: $code", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Verification Code')
                ->from('yocristiangarciaco@gmail.com', 'cris');
        });
    
        return redirect()->route('purchase_order.verify');
    }
    

    public function showVerificationForm()
    {
        if (!session()->has('email')) {
            return redirect()->route('purchase_order.email');
        }

        return view('auth.purchase_order_verify');
    }

    public function verifyCode(Request $request)
    {
        
        $request->validate(['code' => 'required|numeric']);
        

        if ($request->code == session('verification_code')) {
            $email = session('email');
            $user = User::where('email', $email)->first();

            if (!$user) {
                return redirect()->route('purchase_order.email')->withErrors(['email' => 'Email not found.']);
            }

            Auth::login($user);
            session()->forget(['verification_code', 'email']);
            return redirect()->route('purchase_order.dashboard');
        } else {
            return redirect()->route('purchase_order.verify')->withErrors(['code' => 'Invalid verification code.']);
        }
    }

    public function showDashboard()
    {
        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->first();
        $branchOffices = BranchOffice::where('client_id', $client->id)->get();
        $products = Product::where('client_id', $client->id)->get();

        
    
        return view('purchase_order.dashboard', compact('client', 'branchOffices','products'));
    }


  
    public function storeClientPurchase(Request $request)
    {

        $request->validate([
            'branch_office_id' => 'required|exists:branch_offices,id',
            'required_delivery_date' => 'required|date|after:today',
            'order_consecutive' => 'required|string|unique:purchase_orders,order_consecutive',
            'delivery_address' => 'required|string',
            'observations' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->first();

        $purchaseOrder = new PurchaseOrder();
        $purchaseOrder->client_id = $client->id;
        $purchaseOrder->branch_office_id = $request->branch_office_id;
        $purchaseOrder->order_creation_date = now(); // Setting the order creation date to the current date
        $purchaseOrder->required_delivery_date = $request->required_delivery_date;
        $purchaseOrder->order_consecutive = $request->order_consecutive;
        $purchaseOrder->delivery_address = $request->delivery_address;
        $purchaseOrder->observations = $request->observations;
        $purchaseOrder->save();

        foreach ($request->products as $product) {
            $purchaseOrder->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
                'price' => Product::find($product['product_id'])->price, // Assuming you have a price field in the products table
            ]);
        }

        return redirect()->route('purchase_order.dashboard')->with('message', 'Orden de compra creada exitosamente.');
    }
}
