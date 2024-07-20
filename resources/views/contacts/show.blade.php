    <a href="/contacts">{{__('pagination.back')}}<br>
    {{--    다국어 파라미터 정의--}}
    <a href="/contacts">{{__('pagination.back2', ['section' => 'contacts'])}}</a><br>

    {{--다국어 복수 표기방법--}}
    <?php $numTaskDeleted = 2; ?>
    @if($numTaskDeleted > 0)
        {{ trans_choice('pagination.task-deletion', $numTaskDeleted) }} <br>
    @endif

    <?php $numTaskDeleted2 = 20; ?>
    {{ trans_choice('pagination.task-deletion2', $numTaskDeleted2) }}

