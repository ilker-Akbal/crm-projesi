<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductSerial;

class ProductSerialController extends Controller
{
    // Seri numaralarını listeler
    public function index()
    {
        // Sadece kendi müşterinize ait ürünlerin seri numaralarını çekin
        $serials = ProductSerial::with('product')
            ->whereHas('product', function($q) {
                $q->where('customer_id', Auth::user()->customer_id);
            })
            ->orderBy('product_id')
            ->orderBy('serial_number')
            ->get();

        return view('product_serials.index', compact('serials'));
    }

    // Yeni seri numarası formu
    public function create()
    {
        $products = Product::where('customer_id', Auth::id())
                           ->orderBy('product_name')
                           ->get();

        return view('product_serials.create', compact('products'));
    }

    // Gelen veriyi kaydeder
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'   => 'required|exists:products,id',
            'serials_bulk' => 'nullable|string',
            'serials.*'    => 'nullable|string|distinct',
        ]);

        // Toplu ekleme
        if (!empty($data['serials_bulk'])) {
            $lines = preg_split('/\r\n|\r|\n/', trim($data['serials_bulk']));
            foreach ($lines as $sn) {
                $sn = trim($sn);
                if ($sn) {
                    ProductSerial::create([
                        'product_id'    => $data['product_id'],
                        'serial_number' => $sn,
                        'created_by'    => Auth::id(),
                    ]);
                }
            }
        }

        // Dinamik tek tek ekleme
        if (!empty($data['serials']) && is_array($data['serials'])) {
            foreach ($data['serials'] as $sn) {
                $sn = trim($sn);
                if ($sn) {
                    ProductSerial::create([
                        'product_id'    => $data['product_id'],
                        'serial_number' => $sn,
                        'created_by'    => Auth::id(),
                    ]);
                }
            }
        }

        return redirect()->route('product_serials.index')
                         ->with('success', 'Seri numaraları eklendi.');
    }

    // Seriyi siler
    public function destroy(ProductSerial $productSerial)
    {
        // Yetki kontrolü (isteğe bağlı)
        if ($productSerial->product->customer_id !== Auth::id()) {
            abort(403);
        }

        $productSerial->delete();
        return back()->with('success', 'Seri numarası silindi.');
    }
}
