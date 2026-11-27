<div class="mb-3">
    <label class="form-label">Nazwa produktu</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Kategoria</label>
    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
        <option value="">-- wybierz --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if($categories->isEmpty())
        <div class="form-text text-danger">Brak kategorii. <a href="{{ route('manage.categories.index') }}">Dodaj pierwszą kategorię</a>.</div>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Opis</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Cena (zł)</label>
        <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" placeholder="np. 19.99" required>
        @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-4">
        <label class="form-label">Ilość w magazynie</label>
        <input type="number" name="stock" min="0" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock ?? 0) }}" required>
        @error('stock')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
