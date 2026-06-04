@extends('layouts.app')
@section('title', '我的寵物 — 禮寵 Reiko Pet')

@section('content')

<section class="pt-32 pb-20 bg-cream min-h-screen">
    <div class="max-w-4xl mx-auto px-6 lg:px-10">

        <div class="flex items-end justify-between mb-14">
            <div>
                <p class="text-[11px] tracking-[0.4em] uppercase text-muted mb-3">Member Center</p>
                <h1 class="font-serif text-4xl text-ink tracking-widest leading-relaxed">我的寵物</h1>
            </div>
        </div>

        {{-- Nav Tabs --}}
        @include('member._tabs')

        @if(session('success'))
        <div class="mb-8 bg-accent/10 border border-accent/20 px-5 py-4 text-xs text-accent tracking-wide">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="mb-8 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
        @endif

        <div class="grid md:grid-cols-2 gap-8">

            {{-- Pet List --}}
            <div class="space-y-4">
                @forelse($pets as $pet)
                <div class="bg-white border border-border p-7 group" id="pet-{{ $pet->id }}">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="font-serif text-lg text-ink tracking-widest">{{ $pet->name }}</p>
                            <p class="text-xs text-muted tracking-wide mt-1">
                                {{ ['dog'=>'狗狗','cat'=>'貓咪','other'=>'其他'][$pet->type] ?? $pet->type }}
                                @if($pet->breed) · {{ $pet->breed }} @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="toggleEdit({{ $pet->id }})"
                                    class="text-xs text-muted hover:text-accent transition-colors tracking-wide">編輯</button>
                            <form method="POST" action="{{ route('member.pets.destroy', $pet) }}"
                                  onsubmit="return confirm('確定要刪除 {{ $pet->name }} 嗎？')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-muted hover:text-red-400 transition-colors tracking-wide">刪除</button>
                            </form>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-xs text-muted tracking-wide">
                        @if($pet->weight) <span>{{ $pet->weight }} kg</span> @endif
                        @if($pet->birthday) <span>{{ \Carbon\Carbon::parse($pet->birthday)->format('Y/m/d') }}</span> @endif
                        @if($pet->gender) <span>{{ $pet->gender === 'male' ? '公' : '母' }}</span> @endif
                    </div>

                    {{-- Edit Form (hidden) --}}
                    <form id="edit-{{ $pet->id }}" method="POST" action="{{ route('member.pets.update', $pet) }}"
                          class="hidden mt-6 pt-5 border-t border-border space-y-4">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] tracking-widest uppercase text-muted mb-1.5">名字</label>
                                <input type="text" name="name" value="{{ $pet->name }}" required
                                       class="w-full border border-border px-3 py-2.5 text-sm focus:outline-none focus:border-accent transition-colors">
                            </div>
                            <div>
                                <label class="block text-[10px] tracking-widest uppercase text-muted mb-1.5">種類</label>
                                <select name="type" class="w-full border border-border px-3 py-2.5 text-sm bg-white focus:outline-none focus:border-accent">
                                    <option value="dog" {{ $pet->type === 'dog' ? 'selected' : '' }}>狗狗</option>
                                    <option value="cat" {{ $pet->type === 'cat' ? 'selected' : '' }}>貓咪</option>
                                    <option value="other" {{ $pet->type === 'other' ? 'selected' : '' }}>其他</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] tracking-widest uppercase text-muted mb-1.5">品種</label>
                                <input type="text" name="breed" value="{{ $pet->breed }}"
                                       class="w-full border border-border px-3 py-2.5 text-sm focus:outline-none focus:border-accent transition-colors">
                            </div>
                            <div>
                                <label class="block text-[10px] tracking-widest uppercase text-muted mb-1.5">體重 (kg)</label>
                                <input type="number" step="0.1" name="weight" value="{{ $pet->weight }}"
                                       class="w-full border border-border px-3 py-2.5 text-sm focus:outline-none focus:border-accent transition-colors">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="toggleEdit({{ $pet->id }})"
                                    class="text-xs text-muted tracking-widest hover:text-ink transition-colors">取消</button>
                            <button type="submit"
                                    class="bg-accent text-cream text-xs tracking-widest px-6 py-2.5 hover:bg-accent-light transition-colors">儲存</button>
                        </div>
                    </form>
                </div>
                @empty
                <p class="text-center py-12 text-muted tracking-widest border border-border bg-white">尚無登記寵物</p>
                @endforelse
            </div>

            {{-- Add Pet Form --}}
            <div class="bg-white border border-border p-8 h-fit">
                <h2 class="font-serif text-lg text-ink tracking-widest mb-7">新增寵物</h2>
                <form method="POST" action="{{ route('member.pets.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">名字 *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
                               placeholder="例：小白">
                    </div>
                    <div>
                        <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">種類 *</label>
                        <select name="type" required class="w-full border border-border px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                            <option value="">請選擇</option>
                            <option value="dog">狗狗</option>
                            <option value="cat">貓咪</option>
                            <option value="other">其他</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">品種</label>
                        <input type="text" name="breed" value="{{ old('breed') }}"
                               class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">體重 (kg)</label>
                            <input type="number" step="0.1" name="weight" value="{{ old('weight') }}"
                                   class="w-full border border-border px-4 py-3 text-sm text-ink focus:outline-none focus:border-accent transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">性別</label>
                            <select name="gender" class="w-full border border-border px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                                <option value="">不填</option>
                                <option value="male">公</option>
                                <option value="female">母</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">生日</label>
                        <input type="date" name="birthday" value="{{ old('birthday') }}"
                               class="w-full border border-border px-4 py-3 text-sm text-ink bg-white focus:outline-none focus:border-accent">
                    </div>
                    <button type="submit"
                            class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-4
                                   hover:bg-accent-light transition-colors duration-300">
                        新增
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

@push('scripts')
<script>
function toggleEdit(id) {
    document.getElementById('edit-' + id).classList.toggle('hidden');
}
</script>
@endpush

@endsection
