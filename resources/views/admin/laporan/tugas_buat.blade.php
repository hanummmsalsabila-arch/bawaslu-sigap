@extends('layouts.app')
@section('title', 'Buat Tugas')

@section('content')
    <h2 class="page">Buat Tugas</h2>
    <p class="sub">Tugas hanya muncul untuk petugas dengan jabatan yang dipilih.</p>

    <div class="panel">
        <form class="form" method="POST" action="{{ route('admin.tugas.simpan') }}">
            @csrf

            <label for="judul">Judul tugas</label>
            <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required>

            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi">{{ old('deskripsi') }}</textarea>

            <label for="jabatan">Untuk jabatan</label>
            <select id="jabatan" name="jabatan" required>
                @foreach (\App\Models\Tugas::JABATAN as $kode => $nama)
                    <option value="{{ $kode }}" @selected(old('jabatan') === $kode)>{{ $nama }}</option>
                @endforeach
            </select>

            <label for="user_id">Khusus petugas tertentu (opsional)</label>
            <select id="user_id" name="user_id">
                <option value="">Semua petugas di jabatan tersebut</option>
                @foreach ($petugas as $p)
                    <option value="{{ $p->id }}" @selected((string) old('user_id') === (string) $p->id)>
                        {{ $p->name }} - {{ $p->labelJabatan() }}
                    </option>
                @endforeach
            </select>

            <label for="batas_waktu">Batas waktu (opsional)</label>
            <input type="datetime-local" id="batas_waktu" name="batas_waktu" value="{{ old('batas_waktu') }}">

            <div class="actions">
                <button class="btn" type="submit">Simpan Tugas</button>
                <a class="btn btn-dark" href="{{ route('admin.tugas') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection