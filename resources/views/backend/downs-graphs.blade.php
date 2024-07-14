{{-- 뷰에서 서비스 객체를 바로 주입하기 --}}
@inject('analytics', 'App\Models\Task' )

<div class="finances-display">
    {{ $analytics->recent() }}
</div>
down
