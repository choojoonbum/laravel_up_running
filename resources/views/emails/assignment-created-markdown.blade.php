@component('mail::message')
    # 안녕하세요! {{ $trainee->name }}!

    **{{ $trainer->name }}** 으로 부터 새 운동을 할당받았습니다.

    {{--마크다운 컴포넌트--}}
    @component('mail::button', ['url' => route('training-dashboard')])
        운동을 확인하세요.
    @endcomponent

    감사합니다. <br>
    {{ config('app.name') }}
@endcomponent
