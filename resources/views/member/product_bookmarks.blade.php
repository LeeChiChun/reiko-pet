@extends('layouts.app')
@section('title', '收藏商品 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-5xl mx-auto px-6 lg:px-10">

        <div class="flex items-end justify-between mb-14">
            <div>
                <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Member Center</p>
                <h1 class="font-serif text-4xl text-ink tracking-widest leading-relaxed">收藏商品</h1>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-muted tracking-widest hover:text-ink transition-colors">登出</button>
            </form>
        </div>

        {{-- Nav Tabs --}}
        @include('member._tabs')

        @if(session('success'))
        <div class="mb-8 bg-accent/10 border border-accent/20 px-5 py-4 text-xs text-accent tracking-wide">
            {{ session('success') }}
        </div>
        @endif

        @if($bookmarks->isEmpty())
        <div class="bg-white border border-border p-14 text-center">
            <p class="text-sm text-muted tracking-wide mb-6">還沒有收藏任何商品</p>
            <a href="{{ route('shop.index') }}"
               class="inline-flex items-center gap-2 bg-accent text-cream text-[11px]
                      tracking-[0.3em] uppercase px-8 py-3">
                前往線上商城
            </a>
        </div>
        @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bookmarks as $bookmark)
            @php $product = $bookmark->product; @endphp
            @if($product)
            <div class="group bg-white border border-border hover:border-accent transition-colors duration-300 flex flex-col">
                <a href="{{ route('shop.show', $product) }}" class="block overflow-hidden">
                    <div class="aspect-square bg-cream-alt flex items-center justify-center overflow-hidden">
                        @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <span class="text-4xl text-warm-gray">🐾</span>
                        @endif
                    </div>
                </a>
                <div class="p-5 flex flex-col flex-1">
                    <p class="text-[10px] tracking-[0.3em] uppercase text-muted mb-2">
                        {{ ['snack'=>'零食點心','toy'=>'玩具','cleaning'=>'清潔用品','clothing'=>'衣著配件','health'=>'保健食品'][$product->category] ?? $product->category }}
                    </p>
                    <a href="{{ route('shop.show', $product) }}"
                       class="font-serif text-sm text-ink tracking-widest leading-relaxed hover:text-accent transition-colors mb-2 line-clamp-2 flex-1">
                        {{ $product->name }}
                    </a>
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-accent font-medium tracking-wide">NT$ {{ number_format($product->price) }}</span>
                        <form method="POST" action="{{ route('member.product-bookmarks.remove', $bookmark) }}"
                              onsubmit="return confirm('確認取消收藏？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-[10px] tracking-[0.25em] uppercase text-muted hover:text-red-400 transition-colors">
                                取消收藏
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        <div class="mt-8">{{ $bookmarks->links() }}</div>
        @endif

    </div>
</section>

@endsection
