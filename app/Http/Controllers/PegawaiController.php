<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    public function index(Request $request) {
        $keyword = $request->get('keyword');
        $sort = $request->get('sort', 'desc');
        $gender = $request->get('gender');
        $pekerjaan_id = $request->get('pekerjaan_id');
        $is_active = $request->get('is_active');
        
        $data = Pegawai::with('pekerjaan')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%");
            })
            ->when($gender, function ($query) use ($gender) {
                $query->where('gender', $gender);
            })
            ->when($pekerjaan_id, function ($query) use ($pekerjaan_id) {
                $query->where('pekerjaan_id', $pekerjaan_id);
            })
            ->when($is_active !== null && $is_active !== '', function ($query) use ($is_active) {
                $query->where('is_active', $is_active);
            })
            ->orderBy('created_at', $sort)
            ->paginate(10)
            ->appends($request->all());
            
        $pekerjaan = Pekerjaan::all();
        return view('pegawai.index', compact('data', 'pekerjaan'));
    }

    public function add() {
        $pekerjaan = Pekerjaan::all();
        return view('pegawai.add', compact('pekerjaan'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|email|unique:pegawai,email',
            'gender' => 'required|in:male,female',
            'pekerjaan_id' => 'required|exists:pekerjaan,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        $data = new Pegawai();
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->gender = $request->gender;
        $data->pekerjaan_id = $request->pekerjaan_id;
        $data->is_active = $request->has('is_active') ? true : false;

        if ($data->save()) {
            return redirect()->route('pegawai.index')->with('success', 'Data berhasil ditambahkan');
        } else {
            return redirect()->route('pegawai.index')->with('error', 'Data tidak tersimpan');
        }
    }

    public function edit(Request $request) {
        $data = Pegawai::findOrFail($request->id);
        $pekerjaan = Pekerjaan::all();
        return view('pegawai.edit', compact('data', 'pekerjaan'));
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|email|unique:pegawai,email,' . $request->id,
            'gender' => 'required|in:male,female',
            'pekerjaan_id' => 'required|exists:pekerjaan,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        $data = Pegawai::findOrFail($request->id);

        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->gender = $request->gender;
        $data->pekerjaan_id = $request->pekerjaan_id;
        $data->is_active = $request->has('is_active') ? true : false;

        if ($data->save()) {
            return redirect()->route('pegawai.index')->with('success', 'Data berhasil diperbarui');
        } else {
            return redirect()->route('pegawai.index')->with('error', 'Data tidak tersimpan');
        }
    }

    public function destroy(Request $request) {
        Pegawai::findOrFail($request->id)->delete();
        return redirect()->route('pegawai.index')->with('success', 'Data berhasil dihapus');
    }
}
