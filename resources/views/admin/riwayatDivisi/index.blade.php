@extends('layout.base')


@section('title', 'Data Riwayat Divisi')


@section('content_header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-hat"></i> <span class="text-semibold">Riwayat Divisi</span>
                    - List Data Pegawai</h4>
            </div>

        </div>

        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><i class="active icon-home2 position-left"></i> List Data Pegawai</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel bg-info">
        <div class="panel-heading">
            <em>
                <h6>Pada halaman merupakan daftar list pegawai yang bisa dipilih untuk dilihat riwayat divisinya. klik saja
                    tombol "Lihat" maka daftar riwayat divisi akan muncul.
                </h6>
            </em>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="close"></a></li>
                </ul>
            </div>

        </div>
    </div>

    <div class="panel panel-flat">

        <div class="panel-body">

            <table class="table datatable-basic table-bordered table-striped table-hover table-xs">
                <thead class="bg-primary">
                    <tr>
                        <th>No</th>
                        <th>Pegawai</th>
                        <th hidden>Nama</th>
                        <th hidden>Jabatan</th>
                        <th hidden>Divisi</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @if ($pegawai->count())
                        @foreach ($pegawai as $key => $p)
                            <tr>
                                <td class="text-center">{{ $i++ }}</td>
                                <td><b>{{ $p->id }}</b></span>
                                    <br>
                                    <span class="label bg-danger">{{ $p->role->nm_role }}</span>
                                    <br>
                                    {{ $p->nama }}
                                    <br>
                                    <span class="label bg-warning">
                                        @if ($p->id_divisi == null)
                                            <b>Belum Ada Divisi</b>
                                        @else
                                            {{ $p->divisi->nm_divisi }}
                                        @endif
                                    </span>
                                    <span class="label bg-teal">

                                        @if ($p->id_jabatan == null)
                                            <b>Belum Ada Jabatan</b>
                                        @else
                                            {{ $p->jabatan->nm_jabatan }}
                                        @endif

                                    </span>
                                    <br>
                                    {{ $p->email . ' / ' . $p->no_hp }}
                                </td>
                                <td hidden></td>
                                <td hidden></td>
                                <td hidden><span class="label label-success">Active</span></td>
                                <td> <?php $encyrpt = Crypt::encryptString($p->id); ?>
                                    <a href="{{ route('riwayatDivisi.show', $encyrpt) }}" class="btn btn bg-info-300"><i
                                            class=" icon-eye"></i> Lihat
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
    <!-- /basic datatable -->

@endsection
