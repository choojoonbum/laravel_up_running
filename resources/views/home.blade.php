{{--@include를 사용해서 개별 템플릿을 포함하는 방법--}}
<div class="content" data-page-name="{{ $pageName }}">
    <p> 지금 바로 웹사이트에 가입하세요!</p>
    @include('sign-up-button', ['text' => '여기를 클릭하세요'])

    {{--조건에 따라 개별 템플릿을 포함하기--}}
    {{--해당 템플릿 파일이 있다면 포함하기--}}
    @includeIf('sidebars.admin', ['some' => 'data'])
    {{--전달된 조건 값이 참인 경우에 해당 템플릿 파일을 포함하기--}}
    @includeWhen(false, 'sidebars.admin', ['some' => 'data'])
    {{--주어진 템플릿 배열 값에서 템플릿 파일이 존재하는지 확인하고 존재하는 첫 번째 템플릿을 포함하기--}}
    @includeFirst(['customs.header', 'header'], ['some' => 'data'])
</div>


<!-- 컴포넌트와 슬롯을 사용한 리펙토링 코드 -->
@component('partials.modal', ['class' => 'danger']/*컴포넌트 지시어에서 슬롯없이 데이터를 전달할수도 있다*/)

    @slot('title')
        비밀번호 유효 검사 실패
    @endslot

    @ifGuest
    <p>비밀번호가 유효하지 않습니다. 비밀번호는 다음과 같은 형식어야 합니다.</p>
    <p><a href="">...</a></p>
    @endif

    <?php $message = '지시어등록
        사용방법'; ?>
    <p>@newlinesToBar($message)</p>

@endcomponent


