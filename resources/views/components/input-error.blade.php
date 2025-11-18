@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'error-message']) }} style="list-style: none; padding: 0; margin: 0;">
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
