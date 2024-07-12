<form action="/tasks/5" method="POST">
    {{--직접 _method hidden 타입을 추가--}}
    <input type="hidden" name="_method" value="DELETE">
    {{--@method  지시어을 사용하여 정의--}}
    @method('DELETE')

    {{--php 코드로 csrf_field() 핼퍼 함수를 호출하거나--}}
    <?php echo csrf_field(); ?>
    {{--직접 _token 타입을 추가하거나--}}
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    {{-- @csrf지시어 사용--}}
    @csrf
</form>

{{--자바스크립트로 token 적용시--}}
<meta name="csrf-token" content="<?php echo csrf_token();?>" id="token">
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
