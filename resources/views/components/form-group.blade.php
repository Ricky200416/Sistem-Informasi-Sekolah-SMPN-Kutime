{{-- resources/views/components/form-group.blade.php --}}
{{-- Usage: <x-form-group label="Nama" name="nama"> ... </x-form-group> --}}

@props(['label', 'name', 'required' => false])

<div {{ $attributes->class([]) }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>
    {{ $slot }}
    @error($name)
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>