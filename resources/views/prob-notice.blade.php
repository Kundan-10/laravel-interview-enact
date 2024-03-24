<?php

use App\Models\Prize;

$current_probability = floatval(Prize::sum('probability'));
?>
{{-- Display success message if it exists --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- Display error message if it exists --}}
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

{{-- TODO: add Message logic here --}}
