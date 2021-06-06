@extends('layouts.app')

@section('content')

    <your-stuff-base
        :filters=" {{ json_encode($filters) }}"
        :filter-count="{{ json_encode($filterCount) }}"
    >
    </your-stuff-base>

@endsection