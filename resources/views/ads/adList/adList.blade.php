@extends('layouts.app')

@section('content')

<ad-list-base
    :saved-filter-id="{{ json_encode($savedFilterId) }}"
    ad-type-for-url="{{ $adTypeForUrl }}"
    :ads="{{ json_encode($ads) }}"
    :saved-filter="{{ json_encode($filter) }}"
></ad-list-base>

@endsection
