<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminFieldController extends Controller
{
    public function index()
    {
        $fields = Field::all();
        return view('admin.fields.index', compact('fields'));
    }

    public function store(Request $request)
    {
        $validated =$request->validate([
            'name'           => 'required|string|max:100',
            'type'           => 'required|in:indoor,outdoor',
            'price_per_hour' => 'required|numeric|min:1000',
            'image_file'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_url'      => 'nullable|url',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $path =$request->file('image_file')->store('fields', 'public');
            $imagePath = '/storage/' .$path;
        } elseif ($request->filled('image_url')) {
            $imagePath =$request->input('image_url');
        }

        Field::create([
            'name'           => $validated['name'],
            'type'           => $validated['type'],
            'price_per_hour' => $validated['price_per_hour'],
            'image'          => $imagePath,
            'is_active'      => true,
        ]);

        return back()->with('success', 'Lapangan baru berhasil ditambahkan.');
    }

    public function update(Request $request, Field$field)
    {
        $validated =$request->validate([
            'name'           => 'required|string|max:100',
            'type'           => 'required|in:indoor,outdoor',
            'price_per_hour' => 'required|numeric|min:1000',
            'image_file'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_url'      => 'nullable|url',
            'is_active'      => 'required|boolean',
        ]);

        $imagePath =$field->image;

        // Jika upload file baru dari perangkat
        if ($request->hasFile('image_file')) {
            // Hapus file fisik lama jika disimpan di storage lokal
            if ($field->image && str_starts_with($field->image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $field->image));
            }
            $path =$request->file('image_file')->store('fields', 'public');
            $imagePath = '/storage/' .$path;
        } elseif ($request->filled('image_url')) {
            // Jika menginput URL baru
            $imagePath =$request->input('image_url');
        }

        $field->update([
            'name'           => $validated['name'],
            'type'           => $validated['type'],
            'price_per_hour' => $validated['price_per_hour'],
            'image'          => $imagePath,
            'is_active'      => $validated['is_active'],
        ]);

        return back()->with('success', 'Data lapangan berhasil diperbarui.');
    }

    public function destroy(Field $field)
    {
        if ($field->image && str_starts_with($field->image, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $field->image));
        }
        $field->delete();
        return back()->with('success', 'Lapangan berhasil dihapus.');
    }
}
