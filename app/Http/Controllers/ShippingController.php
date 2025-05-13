<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\RajaOngkirService;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;



class ShippingController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Menampilkan halaman shipping dengan daftar cart.
     */
    public function shipping()
    {
        // dd(session()->all());   
        // session()->forget('shipping');
        $shippingData = session()->get('shipping', []);
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access your cart.');
        }
        
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();
        
        $cartTotal = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
        $provinces = $this->rajaOngkir->getProvinces();

        return view('layouts.shipping', compact('cartItems', 'cartTotal', 'provinces'));
    }

    /**
     * Mengambil daftar kota berdasarkan provinsi.
     */
    public function getCities(Request $request)
    {
        return response()->json($this->rajaOngkir->getCities($request->province_id));
    }

    /**
     * Menghitung ongkos kirim berdasarkan kota tujuan dan kurir.
     */
    public function getOngkir(Request $request)
    {
        $request->validate([
            'destination' => 'required',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string',
        ]);

        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.api_key')
        ])->asForm()->post(config('services.rajaongkir.base_url') . "/cost", [
            'origin' => 501, // Ganti dengan ID kota asal toko yang benar
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $request->courier,
        ]);

        if ($response->failed() || !isset($response->json()['rajaongkir']['results'])) {
            return response()->json(['error' => 'Gagal mengambil data ongkir.'], 500);
        }

        return response()->json($response->json()['rajaongkir']['results']);
    }

    /**
     * Menyimpan informasi pengirima
    
     * Menyimpan biaya pengiriman ke session.
     */
    public function storeCost(Request $request)
    {
        $request->validate([
            'shipping_cost' => 'required|numeric',
            'name' => 'required|string',
            'email' => 'required|email',
            'province' => 'required',
            'city' => 'required',
            'address' => 'required',
            'courier' => 'required',
            'service' => 'required',
        ]);

        // Ambil nama provinsi & kota
        $provinces = $this->rajaOngkir->getProvinces();
        $selectedProvince = collect($provinces)->firstWhere('province_id', $request->province);
        $provinceName = $selectedProvince['province'] ?? null;
    
        $cities = $this->rajaOngkir->getCities($request->province);
        $selectedCity = collect($cities)->firstWhere('city_id', $request->city);
        $cityName = $selectedCity['city_name'] ?? null;
        $cityName = optional(collect($this->rajaOngkir->getCities($request->province))->firstWhere('city_id', $request->city))['city_name'];

        session()->put('shipping', [
            'cost' => $request->shipping_cost,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'province' => $request->province,
            'province_name' => $provinceName,
            'city' => $request->city,
            'city_name' => $cityName,
            'address' => $request->address,
            'courier' => $request->courier,
            'service' => $request->service,
        ]);
        session()->save();
        

        return redirect()->route('payment.index')->with('success', 'Biaya pengiriman disimpan!');
    }
}
