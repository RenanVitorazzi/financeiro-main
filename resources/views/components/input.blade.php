{{-- @php dd($errors->has('peso')) @endphp --}}
<input {{ $attributes->merge(([
    'class' => $errors->has($attributes['name']) ? 'is-invalid form-control' : 'form-control' , 
    'type' => 'text', 
    'id' => $attributes['name']
])) }}>

@error($attributes['name'])
<div class="invalid-feedback d-inline">
    {{ $message }}
</div>
@enderror