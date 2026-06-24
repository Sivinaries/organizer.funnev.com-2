<?php

namespace App\Http\Controllers;

use App\Models\Act;
use Illuminate\Support\Facades\Cache;

class ActController extends Controller
{
    public function index()
    {
        $acts = Cache::remember('acts', now()->addMinutes(60), fn() =>
            Act::with('user')->latest()->get());

        return view('act', compact('acts'));
    }
}
