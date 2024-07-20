<form method="post" action="{{ route('posts.create') }}?utm=12345">
    @csrf
    <input type="text" name="first_name">
    <input type="text" name="employees[0][firstName]">
    <input type="text" name="employees[0][lastName]">
    <input type="text" name="employees[1][firstName]">
    <input type="text" name="employees[1][lastName]">
    <input type="submit">
</form>
<br>
<form method="post" action="/file" enctype="multipart/form-data">
    @csrf
    name: <input type="text" name="name">
    file: <input type="file" name="profile_picture">
    <input type="submit">
</form>
