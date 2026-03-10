@if ($errors->any())
<div class="alert alert-danger">
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

{{-- Handmatige foutmeldingen (bijv. uit je try-catch) --}}
@if (session('error'))
<div class="alert alert-danger">
  {{ session('error') }}
</div>
@endif