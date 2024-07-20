@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- 라라벨 5.8이상 --}}
@error('first_name')
    <span>{{ $message }}</span>
@enderror
