<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Tampilkan semua data Produk.
     * Beserta pemiliknya (user)
     */
    public function index()
    {
        // Untuk memanggil relasi terkait, sebutkan
        // nama method relasi yang ada di model tersebut.
        // Gunakan method with() untuk menyertakan relasi tabel
        // pada data yang dipanggil.
        $products = Product::query()
            ->where('is_available', true)   // hanya produk tersedia
            ->with('user')                  // sertakan pemiliknya
            ->get();                        // eksekusi query
        // format respon ada status (Sukses/Gagal) dan data
        return response()->json([
            'status' => 'Sukses',
            'data'   => $products
        ]);
    }

    /**
     * Cari produk berdasarkan `name`
     * dan ikutkan relasinya
     */
    public function search(Request $req)
    {
        try {
            // validasi minimal 3 huruf untuk pencarian
            $validated = $req->validate([
                'teks' => 'required|min:3',
            ], [
                // pesan error custom
                'teks.required' => ':Attribute jangan dikosongkan lah!',
                'teks.min'      => 'Ini :attribute kurang dari :min bos!',
            ], [
                // custom attributes
                'teks'  => 'huruf'
            ]);

            // proses pencarian produk berdasarkan teks yang dikirim
            $products = Product::query()
                ->where('name', 'like', '%'.$req->teks.'%')
                ->with('user')
                ->get();
            // return hasil pencarian
            return response()->json([
                'pesan' => 'Sukses!',
                'data'  => $products,
            ]);
        }
        // use Illuminate\Validation\ValidationException;
        catch (ValidationException $ex) {
            return response()->json([
                'pesan' => 'Gagal!',
                'data'  => $ex->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //buat Product baru
        try
        {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image_path'  => 'nullable|string',
            'description' => 'nullable|string',
            'is_available'=> 'boolean',
            'user_id'     => 'nullable|exists:users,id',
        ]);

        $products = Product::create($validated);
        
        return response()->json([
                'pesan' => 'Sukses!',
                'data'  => $products,
            ]);
        }
        catch (ValidationException $ex) {
            return response()->json([
                'pesan' => 'Gagal!',
                'data'  => $ex->getMessage(),
            ]);
        }
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
    public function show(Product $products, $id)
    {
        // menampilkan Product berdasarkan id
        $products = Product::find($id);

        if (!$products) {
            return response()->json(['message' => 'Product tidak ditemukan']);
        }

        return response()->json([
                'pesan' => 'Sukses!',
                'data'  => $products,
            ]);
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
    public function update(Request $request, Product $product, $id)
    {
        //update Product
        try
        {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product tidak ditemukan']);
        }

        $validated = $request->validate([
            'name'        => 'string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image_path'  => 'nullable|string',
            'description' => 'nullable|string',
            'is_available'=> 'boolean',
            'user_id'     => 'nullable|exists:users,id',
        ]);

        $product->update($validated);

        return response()->json([
                'pesan' => 'Sukses!',
                'data'  => $product,
            ]);
        }
        catch (ValidationException $ex) {
            return response()->json([
                'pesan' => 'Gagal!',
                'data'  => $ex->getMessage(),
            ]);
        }
    }

    public function delete(Product $product, $id)
    {
        //hapus Product
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product tidak ditemukan']);
        }

        $product->delete();

        return response()->json([
                'pesan' => 'Berhasil dihapus!',
                'data'  => $product,
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, $id)
    {
        //
    }
}
