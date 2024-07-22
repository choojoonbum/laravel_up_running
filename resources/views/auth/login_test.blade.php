<form action="/login-test" method="post">
    @csrf
    <input type="text" name="email"/>
    <input type="text" name="password"/>
    <input type="submit">
</form>
