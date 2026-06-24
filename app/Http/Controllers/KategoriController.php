<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Cache::remember('kategoris', now()->addMinutes(30), function () {
            return Kategori::all();
        });

        return view('kategori', compact('kategoris'));
    }

    public function create()
    {
        return view("addkategori");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Kategori::create($data);

        $this->clearKateCache();

        return redirect()->route('category')->with('success', 'Category successfully registered!');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('editkategori', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = $request->only(['name']);

        Kategori::where('id', $id)->update($data);

        $this->clearKateCache();

        return redirect()->route('category')->with('success', 'Category updated!');
    }

    public function destroy($id)
    {
        Kategori::destroy($id);

        $this->clearKateCache();

        return redirect()->route('category')->with('success', 'Category Removed!');
    }

    private function clearKateCache()
    {
        Cache::forget('kategoris');
        Cache::forget('kategoris_user');
    }
}
