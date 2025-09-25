<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * tampilkan semua data produk beserta pemiliknya (user)
     */
    public function index()
    {
        //untuk memanggil relasi terkai,sebutkan
        // nama method relasi yang ada di model tersebut
        // gunakan method with() untuk menyertakan relasi tabel
        // pada data yang dipanggil
        $products = Product::query()
        ->where('is_available',true)    // hanya produk yang tersedia
        ->with('user')                          // sertakan pemilik produk
        ->get();                                            // eksekusi query
        // format response ada status (sukses/gagal) dan data
        return response()->json([
            'status' => 'Success',
            'data' => $products
        ]);
    }
    
    /**
     * cari produk berdasarkan nama 
     * dan ikutkan relasinya
     */
    public function search(Request $req)
    {
        // validasi minimal 3 huruf pencarian
        try {
            $validated = $req->validate([
                'teks' => 'required|min:3',
            ],
            [
                // pesan error custom
                'teks.required' => 'Kata kunci harus diisi',
                'teks.min' => 'Kata kunci minimal 3 karakter',
            ], [
                // custom attribute
                'teks'=> 'huruf',
            ]);

            // proses pencarian produk berdasarkan teks yang dikirim
            $products = Product::query()
            ->where('name','like','%'.$req->teks.'%')
            ->with('user')
            ->get();
            // return hasil pencarian
            return response()->json([
                'pesan' => 'Suksus!',
                'data' => $products,
            ]);
        } catch (ValidationException $ex) {
            return response()->json([
                'status' => 'Gagal!',
                'data' => $ex->getMessage(),
            ]);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
