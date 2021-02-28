<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Riwayat_divisi;
use App\Models\Riwayat_jabatan;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $pegawai = Pegawai::sortable()->paginate(10);
        return view('admin.pegawai.index', ['pegawai' => $pegawai]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $jabatan = Jabatan::pluck('nm_jabatan', 'id');
        $divisi = Divisi::pluck('nm_divisi', 'id');
        $role = Role::pluck('nm_role', 'id');
        return view('admin.pegawai.create', [
            'jabatan' => $jabatan,
            'divisi' => $divisi,
            'role' => $role,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'id_role' => 'required',
            'nik' => 'required',
            'nama' => 'required',
            'jk' => 'required',
            'agama' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'alamat_ktp' => 'required',
            'alamat_dom' => 'required',
            'status' => 'required',
            'jml_anak' => 'required',
            'no_hp' => 'required',
            'email' => 'required',
            'id_jabatan' => 'required',
            'id_divisi' => 'required',
            'tgl_masuk' => 'required',
            'imgupload' => 'required|mimes:jpeg,png,jpg,gif,svg|file|max:5000'
        ]);

        $extension = $request->file('imgupload')->extension();
        $imgname = $request->nik . '_' . date('dmyHi') . '.' . $extension;
        $path = Storage::putFileAs('public/images', $request->file('imgupload'), $imgname);
        $id = IdGenerator::generate(['table' => 'pegawai', 'length' => 8, 'prefix' => date('ym')]);
        $password = Str::random(12);
        $riwayat_jabatan = Riwayat_jabatan::where('id_pegawai', $id)
            ->where('id_jabatan', $request->id_jabatan)
            ->count();

        $riwayat_divisi = Riwayat_divisi::where('id_pegawai', $id)
            ->where('id_divisi', $request->id_divisi)
            ->count();

        Pegawai::create([
            'id' => $id,
            'id_role' => $request->id_role,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jk' => $request->jk,
            'agama' => $request->agama,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat_ktp' => $request->alamat_ktp,
            'alamat_dom' => $request->alamat_dom,
            'status' => $request->status,
            'jml_anak' => $request->jml_anak,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => $password,
            'tgl_masuk' => $request->tgl_masuk,
            'id_jabatan' => $request->id_jabatan,
            'id_divisi' => $request->id_divisi,
            'path' => $imgname
        ]);


        if ($riwayat_jabatan == 0 || $riwayat_divisi == 0) {

            Riwayat_jabatan::create([
                'id_pegawai' => $id,
                'id_jabatan' => $request->id_jabatan,
                'thn_mulai' => date('Y'),
                'thn_selesai' => date('Y')
            ]);

            Riwayat_divisi::create([
                'id_pegawai' => $id,
                'id_divisi' => $request->id_jabatan,
                'thn_mulai' => date('Y'),
                'thn_selesai' => date('Y')
            ]);
        }

        Alert::success('success', ' Berhasil Input Data !');
        return redirect('pegawai');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($data)
    {
        //
        $id = Crypt::decryptString($data);
        $pegawai = Pegawai::find($id);
        $riwayat_jabatan = Riwayat_jabatan::where('id_pegawai', $id)
            ->orderBy('id')
            ->get();
        $riwayat_divisi = Riwayat_divisi::where('id_pegawai', $id)
            ->orderBy('id')
            ->get();

        // dd([$pegawai,$riwayat_jabatan]);
        return view('admin.pegawai.details', [
            'id' => $id,
            'pegawai' => $pegawai,
            'riwayat_jabatan' => $riwayat_jabatan,
            'riwayat_divisi' => $riwayat_divisi,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($data)
    {
        //
        $id = Crypt::decryptString($data);
        $pegawai = Pegawai::find($id);
        $jabatan = Jabatan::pluck('nm_jabatan', 'id');
        $divisi = Divisi::pluck('nm_divisi', 'id');
        $role = Role::pluck('nm_role', 'id');
        return view('admin.pegawai.edit', [
            'id' => $data,
            'pegawai' => $pegawai,
            'jabatan' => $jabatan,
            'divisi' => $divisi,
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $data)
    {
        //
        $id = Crypt::decryptString($data);

        $riwayat_jabatan = Riwayat_jabatan::where('id_pegawai', $id)
            ->where('id_jabatan', $request->id_jabatan)
            ->count();

        $riwayat_divisi = Riwayat_divisi::where('id_pegawai', $id)
            ->where('id_divisi', $request->id_divisi)
            ->count();


        if ($request->hasFile('imgupload')) {



            $extension = $request->file('imgupload')->extension();
            $imgname = $request->nik . '_' . date('dmyHi') . '.' . $extension;

            $this->validate($request, [
                'id_role' => 'required',
                'nik' => 'required',
                'nama' => 'required',
                'jk' => 'required',
                'agama' => 'required',
                'tempat_lahir' => 'required',
                'tgl_lahir' => 'required',
                'alamat_ktp' => 'required',
                'alamat_dom' => 'required',
                'status' => 'required',
                'jml_anak' => 'required',
                'no_hp' => 'required',
                'email' => 'required',
                'id_jabatan' => 'required',
                'id_divisi' => 'required',
                'tgl_masuk' => 'required',
                'imgupload' => 'required|mimes:jpeg,png,jpg,gif,svg|file|max:5000'
            ]);



            $pegawai = Pegawai::find($id);
            $path = Storage::putFileAs('public/images', $request->file('imgupload'), $imgname);


            $pegawai->id_role = $request->id_role;
            $pegawai->nik = $request->nik;
            $pegawai->nama = $request->nama;
            $pegawai->jk = $request->jk;
            $pegawai->agama = $request->agama;
            $pegawai->tempat_lahir = $request->tempat_lahir;
            $pegawai->tgl_lahir = $request->tgl_lahir;
            $pegawai->alamat_ktp = $request->alamat_ktp;
            $pegawai->alamat_dom = $request->alamat_dom;
            $pegawai->status = $request->status;
            $pegawai->jml_anak = $request->jml_anak;
            $pegawai->no_hp = $request->no_hp;
            $pegawai->email = $request->email;
            $pegawai->id_jabatan = $request->id_jabatan;
            $pegawai->id_divisi = $request->id_divisi;
            $pegawai->tgl_masuk = $request->tgl_masuk;
            $pegawai->password = $request->password;
            $pegawai->path = $imgname;
            $pegawai->save();

            if ($riwayat_jabatan == 0 || $riwayat_divisi == 0) {

                Riwayat_jabatan::create([
                    'id_pegawai' => $id,
                    'id_jabatan' => $request->id_jabatan,
                    'thn_mulai' => date('Y'),
                    'thn_selesai' => date('Y')
                ]);

                Riwayat_divisi::create([
                    'id_pegawai' => $id,
                    'id_divisi' => $request->id_jabatan,
                    'thn_mulai' => date('Y'),
                    'thn_selesai' => date('Y')
                ]);
            }

            Alert::success('success', ' Berhasil Update Data !');
            return redirect(route('pegawai.details', $data));
        } else {

            $this->validate($request, [
                'id_role' => 'required',
                'nik' => 'required',
                'nama' => 'required',
                'jk' => 'required',
                'agama' => 'required',
                'tempat_lahir' => 'required',
                'tgl_lahir' => 'required',
                'alamat_ktp' => 'required',
                'alamat_dom' => 'required',
                'status' => 'required',
                'jml_anak' => 'required',
                'no_hp' => 'required',
                'email' => 'required',
                'id_jabatan' => 'required',
                'id_divisi' => 'required',
                'tgl_masuk' => 'required',
            ]);

            $pegawai = Pegawai::find($id);

            $pegawai->id_role = $request->id_role;
            $pegawai->nik = $request->nik;
            $pegawai->nama = $request->nama;
            $pegawai->jk = $request->jk;
            $pegawai->agama = $request->agama;
            $pegawai->tempat_lahir = $request->tempat_lahir;
            $pegawai->tgl_lahir = $request->tgl_lahir;
            $pegawai->alamat_ktp = $request->alamat_ktp;
            $pegawai->alamat_dom = $request->alamat_dom;
            $pegawai->status = $request->status;
            $pegawai->jml_anak = $request->jml_anak;
            $pegawai->no_hp = $request->no_hp;
            $pegawai->email = $request->email;
            $pegawai->id_jabatan = $request->id_jabatan;
            $pegawai->id_divisi = $request->id_divisi;
            $pegawai->tgl_masuk = $request->tgl_masuk;
            $pegawai->password = $request->password;
            $pegawai->save();


            if ($riwayat_jabatan == 0 || $riwayat_divisi == 0) {

                Riwayat_jabatan::create([
                    'id_pegawai' => $id,
                    'id_jabatan' => $request->id_jabatan,
                    'thn_mulai' => date('Y'),
                    'thn_selesai' => date('Y')

                ]);

                Riwayat_divisi::create([
                    'id_pegawai' => $id,
                    'id_divisi' => $request->id_jabatan,
                    'thn_mulai' => date('Y'),
                    'thn_selesai' => date('Y')
                ]);
            }

            Alert::success('success', ' Berhasil Update Data !');
            return redirect(route('pegawai.details', $data));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($data)
    {
        //
        $id = Crypt::decryptString($data);
        $pegawai = Pegawai::find($id);
        $pegawai->delete();

        Alert::success('success', ' Berhasil Hapus Data !');
        return redirect(route('pegawai.index'));
    }
}