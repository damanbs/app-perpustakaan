@extends('layouts.app')

@section('title', 'Detail Anggota')

@section('content')
    <h1>Detail Anggota</h1>
    <p><a href="{{ route('members.index') }}">&larr; Kembali ke daftar anggota</a></p>

    <table style="max-width: 600px;">
        <tr>
            <th style="width: 160px; background: #f3f4f6;">ID</th>
            <td>{{ $member['id'] }}</td>
        </tr>
        <tr>
            <th style="background: #f3f4f6;">Nama</th>
            <td>{{ $member['nama'] }}</td>
        </tr>
        <tr>
            <th style="background: #f3f4f6;">NIM</th>
            <td>{{ $member['nim'] }}</td>
        </tr>
        <tr>
            <th style="background: #f3f4f6;">Email</th>
            <td>{{ $member['email'] }}</td>
        </tr>
        <tr>
            <th style="background: #f3f4f6;">No. Telepon</th>
            <td>{{ $member['nomor_telepon'] }}</td>
        </tr>
        <tr>
            <th style="background: #f3f4f6;">Alamat</th>
            <td>{{ $member['alamat'] }}</td>
        </tr>
        <tr>
            <th style="background: #f3f4f6;">Status</th>
            <td>
                <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 13px; font-weight: bold; background: {{ $member['status'] === 'aktif' ? '#dcfce7' : '#fee2e2' }}; color: {{ $member['status'] === 'aktif' ? '#166534' : '#991b1b' }};">
                    {{ ucfirst($member['status']) }}
                </span>
            </td>
        </tr>
    </table>
@endsection
