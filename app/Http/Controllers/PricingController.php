<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PricingController extends Controller
{
    public function index()
    {
        $pricings = Cache::remember('pricings', now()->addMinutes(30), function () {
            return Pricing::all();
        });

        return view('pricing', compact('pricings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'fee' => 'required|integer',
        ]);

        Pricing::create($data);

        $this->clearPricingCache();

        return redirect()->route('pricings')->with('success', 'Pricing successfully registered!');
    }

    public function edit($id)
    {
        $pricing = Pricing::findOrFail($id);

        return view('editpricing', compact('pricing'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fee' => 'required|integer',
        ]);

        $data = $request->only(['name', 'fee']);

        Pricing::where('id', $id)->update($data);

        $this->clearPricingCache();

        return redirect()->route('pricings')->with('success', 'Pricing updated!');
    }

    public function destroy($id)
    {
        Pricing::destroy($id);

        $this->clearPricingCache();

        return redirect()->route('pricings')->with('success', 'Pricing Removed!');
    }

    private function clearPricingCache()
    {
        Cache::forget('pricings');
        Cache::forget('pricings_user');
    }
}
