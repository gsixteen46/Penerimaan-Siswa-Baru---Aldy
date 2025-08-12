<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendaftarController extends Controller
{
    public function index()
    {
        $pendaftars = Pendaftar::with('jurusan')->get(); //mengambil data pendaftar beserta data jurusan yang terkait
        return view('pendaftar.index', compact('pendaftars')); //mengirim data pendaftar ke view index
    }
    public function create()
    {
        $jurusans = Jurusan::all(); //mengambil semua data jurusan untuk dropdown
        return view('pendaftar.create', compact('jurusans')); //menampilkan form pendaftaran
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:255',
            'email'          => 'required|email|unique:pendaftars,email',
            'no_hp'          => 'required|string|max:20',
            'tanggal_lahir'  => 'required|date',
            'alamat'         => 'required|string',
            'asal_sekolah'   => 'required|string|max:255',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'agama'          => 'required|string|max:50',
            'jurusan_id'     => 'required|exists:jurusans,id',
            'berkas'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status'         => 'required|in:pending,diterima,ditolak',
        ]);

        $data = $request->all();

        if ($request->hasFile('berkas')) { //has file untuk mengecek apakah ada berkas yang diupload
            $data['berkas'] = $request->file('berkas')->store('berkas', 'public');//public untuk menyimpan berkkas di storage/app/public
        }

        Pendaftar::create($data);//create ini adalah method dari Eloquent untuk menyimpan data baru ke database

        return redirect()->route('pendaftar.index')->with('success', 'Pendaftar berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        $jurusans = Jurusan::all();//mengambil semua data jurusan karena akan digunakan di form edit
        return view('pendaftar.edit', compact('pendaftar', 'jurusans'));//dua parameter di compact untuk mengirim data pendaftar dan jurusan ke view edit
    }

    public function update(Request $request, $id)//id adalah parameter yang akan digunakan untuk mencari data pendaftar yang akan diupdate
    {
        $request->validate([
            'nama'           => 'required|string|max:255',
            'email'          => 'required|email|unique:pendaftars,email,' . $id, //id berada di baris ini agar email yang sedang diupdate tidak dianggap sebagai duplikat
            'no_hp'          => 'required|string|max:20',
            'tanggal_lahir'  => 'required|date',
            'alamat'         => 'required|string',
            'asal_sekolah'   => 'required|string|max:255',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'agama'          => 'required|string|max:50',
            'jurusan_id'     => 'required|exists:jurusans,id',
            'berkas'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status'         => 'required|in:pending,diterima,ditolak',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('berkas')) {
            {
                // Hapus berkas lama jika ada
                if ($pendaftar->berkas) {
                    Storage::disk('public')->delete($pendaftar->berkas);
                }
                // Simpan berkas baru
                $data['berkas'] = $request->file('berkas')->store('berkas', 'public');
            }
        }

        $pendaftar->update($data);

        return redirect()->route('pendaftar.index')->with('success', 'Pendaftar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->delete();

        return redirect()->route('pendaftar.index')->with('success', 'Pendaftar berhasil dihapus.');
    }
}
