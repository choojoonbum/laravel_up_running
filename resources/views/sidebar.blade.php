{{--@each 지시어를 사용해서 반복문에서 템플릿 사용하기--}}
<div class="header">
    @each('partials.module', $modules, 'module', 'partials.empty-module')
</div>
