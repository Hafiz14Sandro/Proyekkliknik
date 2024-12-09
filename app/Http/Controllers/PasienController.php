<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['pasien'] = \App\Models\Pasien::latest()->paginate(10);
        return view('pasien_index', $data);
    } 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pasien_create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $requestData = $request->validate([
        'nama' => 'required|min:3',
        'no_pasien' => 'required',
        'umur' => 'required',
        'alamat' => 'nullable',
        'jenis_kelamin' => 'required',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:50000',
    ]);

    // Membuat pasien baru
    $pasien = new \App\Models\Pasien;
    $pasien->fill($requestData);

    // Menyimpan foto ke dalam folder 'storage/app/private/public' menggunakan disk 'custom'
    $pasien->foto = $request->file('foto')->store('photos', 'custom');  // 'photos' adalah subfolder di dalam 'private/public'
    
    // Menyimpan data pasien
    $pasien->save();

    flash('Yeay..Data Berhasil Disimpan')->success();
    return back();
}

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pasien = \App\Models\Pasien::findOrFail($id);  // Mencari pasien berdasarkan ID
        return view('pasien_edit', compact('pasien'));  // Pastikan data pasien dikirim ke view
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $requestData = $request->validate([
            'nama' => 'required|min:3',
            'no_pasien' => 'required|unique:pasiens,no_pasien,'.$id,
            'umur' => 'required',
            'alamat' => 'nullable',
            'jenis_kelamin' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5000',
        ]);

        $pasien = \App\Models\Pasien::findOrfail($id);
        $pasien->fill($requestData);
        if ($request->hasfile('foto')) {
            Storage::delete($pasien->foto);
            $pasien->foto =$request->file('foto')->store('public');
        }
        $pasien->save();

        flash('Yeay..Data Berhasil Diupdate')->success();
        return redirect('/pasien');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pasien= \App\Models\Pasien::findOrFail($id);
        if ( $pasien->foto !=null && Storage::exist($pasien->foto)){
            Storage::delete($pasien->foto);
        }
        $pasien->delete();
        flash('Data Berhasil Dihapus');
        return back();
    }
}
