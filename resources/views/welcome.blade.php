@extends('layouts.app')

@section('content')

{{--CITY, PRICE AND ROOM FILTERS  ON WELCOME PAGE--}}
{{--    @include('filters.welcome-home.searchTermFilterLight')--}}

{{-- DISPLAYING THE SAVED FILTERS FOR THE LOGGED IN USER--}}
{{--    @include('filters.welcome-home.filterList')--}}

    <home-base
        :filters=" {{ json_encode($filters) }} "
        :filter-count="{{ json_encode($filterCount) }}"
    >
    </home-base>

@endsection








