{{--블레이드 레이아웃 확장하기--}}
@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    애플리케이션 대시보드에 오신것을 환영합니다.
@endsection

@section('footerScripts')
    @parent
    <script src="dashboard.js"></script>
@endsection
