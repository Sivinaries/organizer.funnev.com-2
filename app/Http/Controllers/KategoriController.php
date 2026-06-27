<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class KategoriController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->level !== 'Admin') {
            abort(403, 'Unauthorized');
        }

        $kategoris = Cache::remember('kategoris', now()->addMinutes(30), function () {
            return Kategori::latest()->get();
        });

        return view('kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $kategori = Kategori::create($data);

        $this->logActivity(
            'Category Created',
            'Category "' . $kategori->name . '" has been created',
            auth()->id()
        );

        $this->clearKateCache();

        return redirect()->route('category')->with('success', 'Category successfully registered!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $kategori = Kategori::findOrFail($id);
        $oldName = $kategori->name;

        $data = $request->only(['name']);
        Kategori::where('id', $id)->update($data);

        $this->logActivity(
            'Category Updated',
            'Category "' . $oldName . '" has been updated to "' . $data['name'] . '"',
            auth()->id()
        );

        $this->clearKateCache();

        return redirect()->route('category')->with('success', 'Category updated!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategoriName = $kategori->name;

        Kategori::destroy($id);

        $this->logActivity(
            'Category Deleted',
            'Category "' . $kategoriName . '" has been deleted',
            auth()->id()
        );

        $this->clearKateCache();

        return redirect()->route('category')->with('success', 'Category Removed!');
    }

    private function clearKateCache()
    {
        Cache::forget('kategoris');
        Cache::forget('kategoris_user');
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
