@php
$steps = ['選擇寵物', '選擇服務', '加值項目', '門市時間', '付款'];
@endphp
<div class="flex items-center gap-0 mb-14 overflow-x-auto">
    @foreach($steps as $i => $label)
    @php $num = $i + 1; @endphp
    <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }} shrink-0">
        <div class="flex flex-col items-center">
            <div class="w-8 h-8 rounded-full border flex items-center justify-center text-xs font-medium transition-all
                        {{ $num < $current ? 'bg-accent border-accent text-cream'
                           : ($num === $current ? 'border-accent text-accent'
                                                 : 'border-border text-muted') }}">
                @if($num < $current)
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                @else
                {{ $num }}
                @endif
            </div>
            <p class="text-[10px] tracking-wide mt-2 whitespace-nowrap
                      {{ $num === $current ? 'text-accent' : 'text-muted' }}">{{ $label }}</p>
        </div>
        @if(!$loop->last)
        <div class="flex-1 h-px mx-2 {{ $num < $current ? 'bg-accent' : 'bg-border' }}"></div>
        @endif
    </div>
    @endforeach
</div>
