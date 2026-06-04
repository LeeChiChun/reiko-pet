@extends('layouts.app')
@section('title', '寵物住宿 — 禮寵 Reiko Pet')

@section('content')

{{-- Hero --}}
<section class="pt-32 pb-16 bg-ink">
    <div class="max-w-6xl mx-auto px-6 lg:px-10 text-center">
        <div class="flex items-center justify-center gap-4 mb-6">
            <span class="w-8 h-px bg-cream/20 block"></span>
            <span class="text-[10px] tracking-[0.5em] uppercase text-cream/40">Pet Accommodation</span>
            <span class="w-8 h-px bg-cream/20 block"></span>
        </div>
        <h1 class="font-serif text-4xl lg:text-5xl text-cream tracking-widest leading-relaxed mb-6">
            {{ $settings['hero_title'] }}
        </h1>
        <div class="h-px bg-cream/15 w-16 mx-auto mb-6"></div>
        <p class="text-sm text-cream/50 tracking-wide leading-loose max-w-xl mx-auto font-light">
            {!! nl2br(e($settings['hero_subtitle'])) !!}
        </p>
    </div>
</section>

{{-- ① 住宿福利 --}}
<section class="py-20 bg-cream border-t border-border">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">
        <div class="mb-14">
            <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-3">What We Offer</p>
            <h2 class="font-serif text-3xl text-ink tracking-widest">住宿福利</h2>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($settings['benefits'] as $b)
            <div class="group">
                <div class="text-2xl mb-4">{{ $b['icon'] }}</div>
                <h4 class="text-sm text-ink tracking-wide mb-2 group-hover:text-accent transition-colors">{{ $b['title'] }}</h4>
                <p class="text-xs text-muted leading-relaxed font-light">{{ $b['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ② 住宿須知 --}}
<section class="py-20 bg-cream-alt border-t border-border">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">
        <div class="mb-12">
            <p class="text-[10px] tracking-[0.4em] uppercase text-muted mb-3">Important Notes</p>
            <h2 class="font-serif text-3xl text-ink tracking-widest">住宿須知</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            @foreach($settings['rules'] as $rule)
            <div>
                <h4 class="text-sm text-ink tracking-widest mb-5 flex items-center gap-3">
                    <span class="w-4 h-px bg-accent block"></span>
                    {{ $rule['title'] }}
                </h4>
                <ul class="space-y-4">
                    @foreach($rule['items'] as $item)
                    <li class="flex gap-4 text-sm text-muted leading-relaxed font-light">
                        <span class="text-accent mt-1 shrink-0">·</span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ③ 住宿房型 + 預約表單 --}}
<section class="py-20 bg-cream-alt" id="rooms">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">

        <div class="text-center mb-14">
            <p class="text-[10px] tracking-[0.5em] uppercase text-muted mb-4">Room Types</p>
            <h2 class="font-serif text-3xl text-ink tracking-widest">住宿房型</h2>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5 mb-16">
            @foreach($rooms as $room)
            <div class="room-card bg-cream border border-border cursor-pointer transition-all duration-200
                        hover:border-accent hover:shadow-lg select-none"
                 data-room="{{ $room->slug }}" data-price="{{ $room->price_per_night }}"
                 data-max-weight="{{ $room->max_weight ?? '' }}">
                {{-- 圖片（若有）--}}
                @if($room->imageUrl())
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ $room->imageUrl() }}" alt="{{ $room->name }}"
                         class="w-full h-full object-cover">
                </div>
                @endif
                <div class="p-8 text-center">
                    <h3 class="font-serif text-xl text-ink tracking-wide mb-2">{{ $room->name }}</h3>
                    <p class="text-2xl text-accent font-light tracking-wider mb-1">
                        NT${{ number_format($room->price_per_night) }}<span class="text-xs text-muted ml-1">/ 晚</span>
                    </p>
                    @if($room->max_weight)
                    <p class="text-[10px] text-muted tracking-wide mb-5">體重上限 {{ $room->max_weight }} kg</p>
                    @else
                    <p class="text-[10px] text-muted tracking-wide mb-5">不限體重</p>
                    @endif
                    <ul class="space-y-2 text-left">
                        @foreach($room->featuresArray() as $f)
                        <li class="flex items-center gap-2 text-xs text-muted tracking-wide">
                            <span class="w-1 h-1 rounded-full bg-accent inline-block shrink-0"></span>
                            {{ $f }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="px-8 pb-6">
                    <div class="check-indicator hidden w-full border border-accent bg-accent/5 text-accent
                                text-[11px] tracking-[0.3em] uppercase text-center py-2">
                        已選擇
                    </div>
                    <div class="select-indicator w-full border border-border text-muted
                                text-[11px] tracking-[0.3em] uppercase text-center py-2">
                        選擇此房型
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- 預約表單（水平排版）--}}
        <div class="bg-cream border border-border p-10 lg:p-14" id="booking-form">
            <div class="text-center mb-10">
                <h2 class="font-serif text-2xl text-ink tracking-widest mb-2">填寫預約資料</h2>
                <p class="text-xs text-muted tracking-wide">選擇房型 → 填寫資料 → 前往付款</p>
            </div>

            @if($errors->any())
            <div class="mb-8 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide space-y-1">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('accommodation.book') }}" id="accom-form">
                @csrf

                {{-- 房型 + 日期（水平一行）--}}
                <div class="grid md:grid-cols-3 gap-5 mb-6">
                    <div>
                        <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">房型 *</label>
                        <div class="border border-border px-4 py-3 bg-cream-alt flex items-center justify-between">
                            <span id="selected-room-label" class="text-sm text-muted tracking-wide">請先點選上方房型</span>
                            <span id="selected-room-price" class="text-accent tracking-wide text-sm"></span>
                        </div>
                        <input type="hidden" name="room_type" id="room_type" value="{{ old('room_type') }}">
                        @error('room_type')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">入住日期 *</label>
                        <input type="date" name="check_in" id="check_in"
                               value="{{ old('check_in') }}" min="{{ date('Y-m-d') }}"
                               class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                      focus:outline-none focus:border-accent transition-colors">
                        @error('check_in')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">退房日期 *</label>
                        <input type="date" name="check_out" id="check_out"
                               value="{{ old('check_out') }}"
                               class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                      focus:outline-none focus:border-accent transition-colors">
                        @error('check_out')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- 費用預覽 --}}
                <div class="bg-accent/5 border border-accent/20 px-5 py-4 hidden mb-6" id="price-preview">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-muted tracking-wide" id="preview-label">費用預覽</span>
                        <span class="font-serif text-accent text-lg tracking-wide" id="preview-total"></span>
                    </div>
                </div>

                {{-- 寵物資料 + 聯絡資料（水平）--}}
                <div class="grid md:grid-cols-2 gap-8 mb-6">

                    {{-- 寵物資料 --}}
                    <div class="border border-border p-6">
                        <p class="text-[11px] tracking-[0.35em] uppercase text-muted mb-5">寵物資料</p>

                        @auth
                            @if($userPets->isNotEmpty())
                            <div class="mb-4">
                                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">選擇已建立的寵物</label>
                                <select name="pet_id" id="pet_id"
                                        class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                               focus:outline-none focus:border-accent transition-colors">
                                    <option value="">手動輸入</option>
                                    @foreach($userPets as $pet)
                                    <option value="{{ $pet->id }}"
                                            {{ old('pet_id', $userPets->first()?->id) == $pet->id ? 'selected' : '' }}>
                                        {{ $pet->name }}（{{ $pet->type === 'dog' ? '狗' : '貓' }}）
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-[10px] text-muted tracking-wide mb-4">或手動輸入寵物資料</p>
                            @endif
                        @endauth

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">寵物名稱 *</label>
                                <input type="text" name="pet_name" id="pet_name" value="{{ old('pet_name') }}"
                                       class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                              focus:outline-none focus:border-accent transition-colors"
                                       placeholder="毛孩名字">
                                @error('pet_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">種類</label>
                                <select name="pet_type" id="pet_type"
                                        class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                               focus:outline-none focus:border-accent transition-colors">
                                    <option value="">請選擇</option>
                                    <option value="狗" {{ old('pet_type') == '狗' ? 'selected' : '' }}>狗</option>
                                    <option value="貓" {{ old('pet_type') == '貓' ? 'selected' : '' }}>貓</option>
                                    <option value="其他" {{ old('pet_type') == '其他' ? 'selected' : '' }}>其他</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">寵物體重（kg）</label>
                            <input type="number" id="pet_weight" name="pet_weight"
                                   value="{{ old('pet_weight') }}" min="0.1" max="200" step="0.1"
                                   class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                          focus:outline-none focus:border-accent transition-colors"
                                   placeholder="例：5.5">
                            <p class="text-[10px] text-muted mt-1 tracking-wide">填寫後將自動過濾可入住房型</p>
                        </div>
                    </div>

                    {{-- 聯絡資料 --}}
                    <div class="border border-border p-6">
                        <p class="text-[11px] tracking-[0.35em] uppercase text-muted mb-5">聯絡資料</p>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">聯絡人 *</label>
                                <input type="text" name="guest_name"
                                       value="{{ old('guest_name', auth()->user()?->name) }}"
                                       class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                              focus:outline-none focus:border-accent transition-colors">
                                @error('guest_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">電話 *</label>
                                <input type="tel" name="guest_phone" id="guest_phone"
                                       value="{{ old('guest_phone', auth()->user()?->phone) }}"
                                       pattern="^(09[0-9]{8}|0[2-9][0-9\-]{7,11})$"
                                       title="支援手機（09xxxxxxxx）或市話（如 02-2345-6789）格式"
                                       class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                              focus:outline-none focus:border-accent transition-colors"
                                       placeholder="09xxxxxxxx 或 02-xxxx-xxxx">
                                @error('guest_phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-[11px] tracking-[0.3em] uppercase text-muted mb-2">Email *</label>
                                <input type="email" name="guest_email"
                                       value="{{ old('guest_email', auth()->user()?->email) }}"
                                       class="w-full border border-border px-4 py-3 text-sm text-ink bg-white
                                              focus:outline-none focus:border-accent transition-colors">
                                @error('guest_email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="w-full bg-accent text-cream text-sm tracking-[0.3em] uppercase py-4
                                   hover:bg-accent-light transition-colors duration-300">
                        確認並前往付款
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    // ── DOM refs ─────────────────────────────────────────────────────────────
    const cards         = document.querySelectorAll('.room-card');
    const roomTypeInput = document.getElementById('room_type');
    const roomLabel     = document.getElementById('selected-room-label');
    const roomPrice     = document.getElementById('selected-room-price');
    const checkIn       = document.getElementById('check_in');
    const checkOut      = document.getElementById('check_out');
    const preview       = document.getElementById('price-preview');
    const previewLabel  = document.getElementById('preview-label');
    const previewTotal  = document.getElementById('preview-total');
    const petNameInput  = document.getElementById('pet_name');
    const petTypeSelect = document.getElementById('pet_type');
    const petIdSelect   = document.getElementById('pet_id');
    const weightInput   = document.getElementById('pet_weight');
    const form          = document.getElementById('accom-form');

    let selectedPrice = 0;

    const roomLabels = @json($rooms->pluck('name', 'slug')->toArray());

    // ── 費用預覽 ──────────────────────────────────────────────────────────────
    function updatePreview() {
        const inVal  = checkIn.value;
        const outVal = checkOut.value;
        if (!selectedPrice || !inVal || !outVal) { preview.classList.add('hidden'); return; }
        const nights = Math.round((new Date(outVal) - new Date(inVal)) / 86400000);
        if (nights <= 0) { preview.classList.add('hidden'); return; }
        const total = selectedPrice * nights;
        previewLabel.textContent = `${nights} 晚 × NT$${selectedPrice.toLocaleString()} / 晚`;
        previewTotal.textContent = `NT$${total.toLocaleString()}`;
        preview.classList.remove('hidden');
    }

    // ── Item 8: 體重篩選房型 ──────────────────────────────────────────────────
    function filterRoomsByWeight() {
        const w = parseFloat(weightInput ? weightInput.value : '');
        cards.forEach(card => {
            const maxW = card.dataset.maxWeight === '' ? null : parseFloat(card.dataset.maxWeight);
            const overLimit = maxW !== null && !isNaN(w) && w > maxW;

            // 既有的「已選擇/選擇此房型」指示器
            const checkIndicator  = card.querySelector('.check-indicator');
            const selectIndicator = card.querySelector('.select-indicator');

            // 移除舊的超重標籤（避免重複）
            let badge = card.querySelector('.weight-badge');
            if (badge) badge.remove();

            if (overLimit) {
                // 如果此房型正被選中，取消選取
                if (roomTypeInput.value === card.dataset.room) {
                    roomTypeInput.value = '';
                    selectedPrice = 0;
                    roomLabel.textContent = '請先點選上方房型';
                    roomLabel.classList.add('text-muted');
                    roomLabel.classList.remove('text-ink');
                    roomPrice.textContent = '';
                    preview.classList.add('hidden');
                }

                card.classList.add('opacity-40', 'cursor-not-allowed');
                card.classList.remove('border-accent', 'shadow-lg', 'cursor-pointer', 'hover:border-accent', 'hover:shadow-lg');
                if (checkIndicator)  checkIndicator.classList.add('hidden');
                if (selectIndicator) selectIndicator.classList.add('hidden');

                // 插入超重標籤
                badge = document.createElement('div');
                badge.className = 'weight-badge px-8 pb-6';
                badge.innerHTML = '<div class="w-full border border-red-300 bg-red-50 text-red-500 text-[11px] tracking-[0.25em] uppercase text-center py-2">超過體重限制</div>';
                card.appendChild(badge);
            } else {
                card.classList.remove('opacity-40', 'cursor-not-allowed');
                card.classList.add('cursor-pointer', 'hover:border-accent', 'hover:shadow-lg');

                // 恢復指示器狀態
                const isSelected = roomTypeInput.value === card.dataset.room;
                if (checkIndicator)  checkIndicator.classList.toggle('hidden', !isSelected);
                if (selectIndicator) selectIndicator.classList.toggle('hidden', isSelected);
            }
        });
    }

    if (weightInput) {
        weightInput.addEventListener('input', filterRoomsByWeight);
    }

    // ── 房型點選 ──────────────────────────────────────────────────────────────
    cards.forEach(card => {
        card.addEventListener('click', () => {
            // 如果此卡片被禁用（超重）則不允許選取
            if (card.classList.contains('cursor-not-allowed')) return;

            cards.forEach(c => {
                if (c.classList.contains('cursor-not-allowed')) return;
                c.classList.remove('border-accent', 'shadow-lg');
                const ci = c.querySelector('.check-indicator');
                const si = c.querySelector('.select-indicator');
                if (ci) ci.classList.add('hidden');
                if (si) si.classList.remove('hidden');
            });

            card.classList.add('border-accent', 'shadow-lg');
            const ci = card.querySelector('.check-indicator');
            const si = card.querySelector('.select-indicator');
            if (ci) ci.classList.remove('hidden');
            if (si) si.classList.add('hidden');

            const key   = card.dataset.room;
            const price = parseInt(card.dataset.price);
            selectedPrice = price;
            roomTypeInput.value = key;
            roomLabel.textContent = roomLabels[key] || key;
            roomLabel.classList.remove('text-muted');
            roomLabel.classList.add('text-ink');
            roomPrice.textContent = `NT$${price.toLocaleString()} / 晚`;

            if (checkIn.value) {
                const nextDay = new Date(checkIn.value);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOut.min = nextDay.toISOString().split('T')[0];
            }
            updatePreview();
            document.getElementById('booking-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // ── 日期變動 ──────────────────────────────────────────────────────────────
    checkIn.addEventListener('change', () => {
        const nextDay = new Date(checkIn.value);
        nextDay.setDate(nextDay.getDate() + 1);
        checkOut.min = nextDay.toISOString().split('T')[0];
        if (checkOut.value && new Date(checkOut.value) <= new Date(checkIn.value)) {
            checkOut.value = nextDay.toISOString().split('T')[0];
        }
        updatePreview();
    });

    checkOut.addEventListener('change', updatePreview);

    // ── Item 6: 寵物選擇器自動填入 ────────────────────────────────────────────
    @auth
    @if($userPets->isNotEmpty())
    @php
    $userPetsData = $userPets->map(fn($p) => [
        'id'     => $p->id,
        'name'   => $p->name,
        'type'   => $p->type,
        'weight' => $p->weight,
    ])->values()->all();
    @endphp
    const userPets = @json($userPetsData);

    const typeMap = { dog: '狗', cat: '貓', other: '其他' };

    if (petIdSelect) {
        petIdSelect.addEventListener('change', function () {
            const petId = parseInt(this.value);
            const pet   = userPets.find(p => p.id === petId);

            if (pet) {
                petNameInput.value          = pet.name;
                petNameInput.readOnly       = true;
                petNameInput.classList.add('bg-cream-alt');

                const mappedType = typeMap[pet.type] ?? '';
                Array.from(petTypeSelect.options).forEach(opt => {
                    opt.selected = opt.value === mappedType;
                });
                petTypeSelect.disabled = true;
                petTypeSelect.classList.add('bg-cream-alt');

                // 體重自動帶入並觸發篩選
                if (weightInput && pet.weight) {
                    weightInput.value = pet.weight;
                    filterRoomsByWeight();
                }
            } else {
                // 手動輸入
                petNameInput.value    = '';
                petNameInput.readOnly = false;
                petNameInput.classList.remove('bg-cream-alt');

                petTypeSelect.value   = '';
                petTypeSelect.disabled = false;
                petTypeSelect.classList.remove('bg-cream-alt');

                if (weightInput) {
                    weightInput.value = '';
                    filterRoomsByWeight();
                }
            }
        });

        // 若有 old('pet_id')，觸發一次
        if (petIdSelect.value) petIdSelect.dispatchEvent(new Event('change'));
    }
    @endif
    @endauth

    // ── Item 7: 表單送出前驗證 ────────────────────────────────────────────────
    if (form) {
        form.addEventListener('submit', function (e) {
            const errors = [];

            if (!roomTypeInput.value) {
                errors.push('請先點選並選擇住宿房型');
            }

            const checkInVal  = checkIn.value;
            const checkOutVal = checkOut.value;
            const today = new Date(); today.setHours(0,0,0,0);

            if (!checkInVal) {
                errors.push('請填寫入住日期');
            } else if (new Date(checkInVal) < today) {
                errors.push('入住日期不可早於今天');
            }

            if (!checkOutVal) {
                errors.push('請填寫退房日期');
            } else if (checkInVal && new Date(checkOutVal) <= new Date(checkInVal)) {
                errors.push('退房日期必須晚於入住日期');
            }

            if (petNameInput && !petNameInput.value.trim()) {
                errors.push('請填寫寵物名稱');
            }

            const phone = document.getElementById('guest_phone');
            if (phone && !/^09\d{8}$/.test(phone.value.trim())) {
                errors.push('電話請填寫 09 開頭的 10 碼手機號碼');
            }

            const email = document.querySelector('input[name="guest_email"]');
            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                errors.push('請填寫正確的 Email 格式');
            }

            const guestName = document.querySelector('input[name="guest_name"]');
            if (guestName && !guestName.value.trim()) {
                errors.push('請填寫聯絡人姓名');
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });
    }

    // ── 還原 old('room_type') 選取狀態 ──────────────────────────────────────
    const oldRoom = '{{ old('room_type') }}';
    if (oldRoom) {
        const target = document.querySelector(`.room-card[data-room="${oldRoom}"]`);
        if (target) target.click();
    }
})();
</script>
@endpush
