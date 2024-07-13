{{--사용자 오류를 표시하는 모달--}}
<div class="modal">
    <!-- 2개의 변수를 가지고 있는 모달 템플릿(슬롯지시어를 사용해 처리-->
    <div class="modal-header {{ $class }}">{{ $title }}</div>

    <div>{{ $slot }}</div>
    <div class="close button etc">...</div>
</div>


