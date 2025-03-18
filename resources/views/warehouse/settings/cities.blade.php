@extends('layouts.app')
@section('title', 'المدن المخدومة')
@section('content')
    <div class="content">
        <h1>المدن المخدومة</h1>
        <form action="{{ route('warehouse.settings.updateCities') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label>اختر المدن:</label>
                @foreach($cities as $city)
                    <label>
                        <input type="checkbox" name="cities[]" value="{{ $city->id }}"
                            {{ in_array($city->id, $selectedCities) ? 'checked' : '' }}> {{ $city->name }}
                    </label>
                @endforeach
                @error('cities') <span class="error">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn">تحديث المدن</button>
        </form>
    </div>
@endsection
