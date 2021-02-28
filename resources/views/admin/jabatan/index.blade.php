@extends('admin.layouts.base')

@section('page_title', 'List Jabatan')

@section('content')

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">List Jabatan / </li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->

    <div class="card mt-4">
        <div class="card-body">
            @if (session('success_message'))
                <div class="alert alert success">
                    {{ session('success_message') }}
                </div>
            @endif
            <a href="{{ route('jabatan.create') }}" class="btn btn-primary">Tambah Jabatan Baru</a>
            <div class="mb-3"></div>
            <table style="text-align:center" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>@sortablelink('id','ID JABATAN')</th>
                        <th>@sortablelink('nm_jabatan','JABATAN')</th>
                        <th>@sortablelink('golongan','GOLONGAN')</th>
                        <th>@sortablelink('gaji_pokok','GAJI POKOK')</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($jabatan->count())
                        @foreach ($jabatan as $key => $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->nm_jabatan }}</td>
                                <td>{{ $p->golongan }}</td>
                                <td>{{ $p->gaji_pokok }}</td>
                                <td>
                                    <?php $encyrpt = Crypt::encryptString($p->id); ?>
                                    <a href="{{ route('jabatan.edit', $encyrpt) }}" class="btn btn-warning">Edit</a>
                                    <a href="{{ route('jabatan.destroy', $encyrpt) }}"
                                        class="btn btn-danger delete-confirm">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <br />
            <div class="pagenation">

                Page : {{ $jabatan->currentPage() }}
                || Total Data : {{ $jabatan->total() }}
                {{ $jabatan->links() }}

            </div>
        </div>

    </div>


@endsection