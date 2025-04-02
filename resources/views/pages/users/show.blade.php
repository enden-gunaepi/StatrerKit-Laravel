@extends('layouts.master')

@section('content')
    <div class="container">
        <h2>Detail Pengguna</h2>
        <div class="card">
            <div class="card-body">
                <h4>Nama: {{ $user->name }}</h4>
                <p>Email: {{ $user->email }}</p>
                <h5>Role:</h5>
                <ul>
                    @foreach ($user->roles as $role)
                        <li>{{ $role->name }}</li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
@endsection
