{{--@if, @else, @elseif, @endif--}}
@if(count($tasks) === 1)
    1개의 대화 메시지가 있습니다.
@elseif(count($tasks) === 0)
    아무런 대화 메시지가 없습니다.
@else
    {{ count($tasks) }} 개의 대화 메시지가 있습니다.
@endif

{{--
@unless($user->hasPaid())
    "if(false)" 와 같다
@endunless
--}}

@for($i=0; $i < count($tasks); $i++)
    숫자 {{ $i }} <br>
@endfor

{{--
@foreach($talks as $talk)
    - {{ $talk->title }} ({{ $talk->length }}) 분
@endforeach
--}}


@while($item = array_pop($tasks))
    {{ $item }} {{--{{ $item->orSomething() }}--}}
@endwhile

@forelse($tasks as $task)
    $task가 비어있지 않으면 실행<br>
@empty
    $task가 빈경우 실행<br>
@endforelse


@foreach @forelse 안에서 사용할 수 있는 $loop 변수
index, iteration, remaining, count, first, last, even, odd, depth, parent
<ul>
    @foreach($page as $page)
        <li>{{ $loop->iteration }} : {{ $page->title }}</li>
        @if($page->hasChildren())
        <ul>
            @foreach($page->children() as $child)
                <li>{{  $loop->parent->iteration }}.{{ $loop->iteration }} : {{ $child->title }}</li>
            @endforeach
        </ul>
        @endif
    @endforeach
</ul>


