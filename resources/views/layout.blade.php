@extends('statamic::layout')

@section('title', 'complex collection ordering')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="flex-1">@yield('heading')</h1>
        @yield('actions')
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        @yield('main')
    </div>
@endsection
