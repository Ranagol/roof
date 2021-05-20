@extends('layouts.app')

@section('content')

<ad-detail-base
    :ad="{{ json_encode($ad) }}"
    :duplicates="{{ json_encode($duplicates) }}"
></ad-detail-base>

@endsection
