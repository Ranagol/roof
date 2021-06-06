@extends('layouts.app')

@section('content')

    <your-stuff-base
        :filters=" {{ json_encode($filters) }}"
        :filter-count="{{ json_encode($filterCount) }}"
        :dismissed-ads="{{ json_encode($dismissedAds) }}"
        :starred-ads="{{ json_encode($starredAds) }}"
        :user="{{ json_encode($userData) }}"
    >
    </your-stuff-base>

@endsection