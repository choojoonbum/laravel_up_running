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

{{--명령어로 생성한 컴포넌트 뷰 템플릿 사용--}}
<x-alert type="error" :message="$message" id="alertId" name="alertName"></x-alert>
