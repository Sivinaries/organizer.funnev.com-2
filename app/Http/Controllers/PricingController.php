<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PricingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->level !== 'Admin') {
            abort(403, 'Unauthorized');
        }

        $pricings = Cache::remember('pricings', now()->addMinutes(30), function () {
            return Pricing::latest()->get();
        });

        return view('pricing', compact('pricings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'fee' => 'required|integer',
        ]);

        $pricing = Pricing::create($data);

        $this->logActivity(
            'Pricing Created',
            'Pricing "' . $pricing->name . '" with fee "' . $pricing->fee . '" has been created',
            auth()->id()
        );

        $this->clearPricingCache();

        return redirect()->route('pricings')->with('success', 'Pricing successfully registered!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fee' => 'required|integer',
        ]);

        $pricing = Pricing::findOrFail($id);
        $oldName = $pricing->name;
        $oldFee = $pricing->fee;

        $data = $request->only(['name', 'fee']);
        Pricing::where('id', $id)->update($data);

        $this->logActivity(
            'Pricing Updated',
            'Pricing "' . $oldName . '" (' . $oldFee . ') has been updated to "' . $data['name'] . '" (' . $data['fee'] . ')',
            auth()->id()
        );

        $this->clearPricingCache();

        return redirect()->route('pricings')->with('success', 'Pricing updated!');
    }

    public function destroy($id)
    {
        $pricing = Pricing::findOrFail($id);
        $pricingName = $pricing->name;
        $pricingFee = $pricing->fee;

        Pricing::destroy($id);

        $this->logActivity(
            'Pricing Deleted',
            'Pricing "' . $pricingName . '" with fee "' . $pricingFee . '" has been deleted',
            auth()->id()
        );

        $this->clearPricingCache();

        return redirect()->route('pricings')->with('success', 'Pricing Removed!');
    }

    private function clearPricingCache()
    {
        Cache::forget('pricings');
        Cache::forget('pricings_user');
    }

    private function logActivity(string $action, string $description, int $userId): void
    {
        Act::create([
            'user_id' => $userId,
            'action' => strtolower(str_replace(' ', '_', $action)),
            'description' => $description,
        ]);
        Cache::forget('acts');
    }
}
