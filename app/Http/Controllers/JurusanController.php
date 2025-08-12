<?php

namespace App\Http\Controllers;

use App\Models\Jurusan; // import model jurusan
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusans = Jurusan::all(); //meengambil semua data jurusan sama seperti select * from Jurusan di database
        return view('jurusan.index', compact('jurusans'));//mengirim data jurusan ke view index yang ada di folder jurusan  
    }

    public function create()
    {
        return view('jurusan.create');//menampilkan form untuk menambah jurusan
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Jurusan::create($request->all()); //menyimpan data jurusan baru ke database
        return redirect()->route('jurusan.index')->with('success', 'jurusan berhasil ditambahkan.');//redirect ke halaman index jurusan dengan pesan sukses
    }

    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);//mengambil data jurusan berdasarkan id yang diinput
        return view ('jurusan.edit', compact('jurusan'));//menampilkan form edit berdasarkan id jurusan yang dipiih
    }

    public function update(Request $request, $id)
    {
        $request->validate([ //validasi data yang diinputkan
            'nama_jurusan' => 'required|string|max:255',//namam jurusan harus diisi, string, dan maksimal 255 karakter
            'deskripsi' => 'nullable|string',// deskripsi boleh diisi atau tidak, jika diisi harus string
        ]);

        $jurusan = Jurusan::findOrFail($id); //mencari jurusan berdasarkan id yang diberikan
        $jurusan->update($request->all()); //memperbarui data jurusan dengan data yang baru
        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil diperbarui.'); //redirect ke halaman index jurusan dengan pesan sukses
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id); //mencari jurusan berdasarkan id yang diberikan
        $jurusan->delete(); //menghapus jurusan dari database
        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil dihapus.'); //redirect ke halaman index jurusan dengan pesan sukses
    }
}
