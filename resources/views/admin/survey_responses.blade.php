@extends('layouts.admin')
@section('title', '回饋列表')
@section('page-title', 'Survey Responses')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <h1 class="font-serif text-2xl text-ink tracking-widests">回饋列表</h1>
        <a href="{{ route('admin.survey') }}"
           class="text-xs text-muted tracking-widest hover:text-ink transition-colors border border-border px-4 py-2">
            ← 返回題目管理
        </a>
    </div>

    @forelse($responses as $response)
    <div class="bg-white border border-border mb-4 p-6">
        <div class="flex items-center gap-4 mb-4">
            <p class="text-xs text-muted tracking-wide">
                #{{ $response->id }} ·
                {{ $response->user ? $response->user->name : '訪客' }} ·
                {{ $response->created_at->format('Y/m/d H:i') }}
            </p>
        </div>
        <div class="space-y-3">
            @foreach($response->answers as $answer)
            <div class="flex gap-4">
                <p class="text-xs text-muted tracking-wide w-40 shrink-0">{{ $answer->question->title ?? '已刪除題目' }}</p>
                <p class="text-sm text-ink tracking-wide">
                    @if($answer->question && $answer->question->type === 'star')
                        @for($s = 1; $s <= 5; $s++)
                        <span class="{{ $s <= $answer->answer ? 'text-amber-400' : 'text-border' }}">★</span>
                        @endfor
                        <span class="text-xs text-muted ml-1">{{ $answer->answer }}/5</span>
                    @else
                        {{ $answer->answer }}
                    @endif
                </p>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="text-center py-16 text-muted text-sm tracking-wide">尚無任何回饋</div>
    @endforelse

    <div class="mt-8">{{ $responses->links() }}</div>
</div>
@endsection
