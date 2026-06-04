{{-- resources/views/components/form-input.blade.php --}}
{{-- Usage: <x-form-input name="nama" :value="$siswa->nama" /> --}}

@props(['name', 'type' => 'text', 'value' => '', 'placeholder' => '', 'required' => false, 'maxlength' => null])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ old($name, $value) }}"
    placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }}
    {{ $maxlength ? "maxlength=$maxlength" : '' }}
    {{ $attributes->class([
        'w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400',
        'border-red-400' => $errors->has($name),
        'border-slate-300' => !$errors->has($name),
    ]) }}
>