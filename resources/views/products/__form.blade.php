@csrf
<div class="row">
    <div class="col">
        <label for="name" class="required">{{ __("Nombre") }}</label>
        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
               class="form-control @error('name') is-invalid @enderror" required minlength="3" maxlength="30">
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @else
            <small class="form-text text-muted">
                {{ __("Entre 3 y 30 caracteres") }}
            </small>
        @enderror
    </div>
    <div class="col">
        <label for="cost" class="required">{{ __("Costo") }}</label>
        <input type="number" name="cost" id="cost" value="{{ old('cost', $product->cost) }}"
               class="form-control @error('cost') is-invalid @enderror" required min="1" max="9999999">
        @error('cost')
        <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @else
            <small class="form-text text-muted">
                {{ __("Debe ser numérico entre 1 y 9999999") }}
            </small>
            @enderror
    </div>
</div>
<br>
<div class="row">
    <div class="col">
        <label for="description">{{ __("Descripción") }}</label>
        <textarea name="description" id="description" maxlength="255"
                  class="form-control @error('description') is-invalid @enderror">
            {{ old('description', $product->description) }}
        </textarea>
        @error('description')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @else
            <small class="form-text text-muted">
                {{ __("Máximo de caracteres: 255") }}
            </small>
        @enderror
    </div>
</div>
<br>
