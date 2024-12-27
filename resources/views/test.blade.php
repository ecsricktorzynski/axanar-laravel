<!-- resources/views/test.blade.php -->
<form method="POST" action="{{ route('test.csrf') }}">
    @csrf
    <button type="submit">Test</button>
</form>
