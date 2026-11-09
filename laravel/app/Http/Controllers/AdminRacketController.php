<?php

namespace App\Http\Controllers;

use App\Models\Racket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminRacketController extends Controller
{
    public function index()
    {
        $rackets = Racket::orderBy('id', 'desc')->get();
        return view('admin.rackets.index', compact('rackets'));
    }

    public function store(Request $request)
    {
        $validated =$request->validate([
            'name'              => 'required|string|max:100',
            'category'          => 'required|in:racket,ball',
            'brand'             => 'nullable|string|max:100',
            'price_per_session' => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'image_file'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_url'         => 'nullable|url',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $path =$request->file('image_file')->store('rackets', 'public');
            $imagePath = '/storage/' .$path;
        } elseif ($request->filled('image_url')) {
            $imagePath =$request->input('image_url');
        }

        // Tentukan label brand jika kategori raket atau bola
        $brandLabel = $validated['brand'] ?: ($validated['category'] === 'ball' ? 'Beli Baru (Milik Sendiri)' : 'Sewa Raket');

        Racket::create([
            'name'              => $validated['name'],
            'brand'             => $brandLabel,
            'price_per_session' => $validated['price_per_session'],
            'stock'             => $validated['stock'],
            'image'             => $imagePath,
            'is_active'         => true,
        ]);

        return back()->with('success', 'Item perlengkapan baru berhasil ditambahkan.');
    }

    public function update(Request $request, Racket$racket)
    {
        $validated =$request->validate([
            'name'              => 'required|string|max:100',
            'brand'             => 'required|string|max:100',
            'price_per_session' => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'is_active'         => 'required|boolean',
            'image_file'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_url'         => 'nullable|url',
        ]);

        $imagePath =$racket->image;

        if ($request->hasFile('image_file')) {
            if ($racket->image && str_starts_with($racket->image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $racket->image));
            }
            $path =$request->file('image_file')->store('rackets', 'public');
            $imagePath = '/storage/' .$path;
        } elseif ($request->filled('image_url')) {
            $imagePath =$request->input('image_url');
        }

        $racket->update([
            'name'              => $validated['name'],
            'brand'             => $validated['brand'],
            'price_per_session' => $validated['price_per_session'],
            'stock'             => $validated['stock'],
            'is_active'         => $validated['is_active'],
            'image'             => $imagePath,
        ]);

        return back()->with('success', 'Data perlengkapan berhasil diperbarui.');
    }

    public function destroy(Racket $racket)
    {
        if ($racket->image && str_starts_with($racket->image, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $racket->image));
        }
        $racket->delete();
        return back()->with('success', 'Item perlengkapan berhasil dihapus.');
    }
}
