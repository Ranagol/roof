@extends('layouts.app')

@section('content')

    <home-base
        :filters=" {{ json_encode($filters) }} "
        :filter-count="{{ json_encode($filterCount) }}"
    >
    </home-base>

@endsection








