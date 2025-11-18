@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'success-message']) }}>
        <i class="fas fa-check-circle"></i> {{ $status }}
    </div>
@endif
