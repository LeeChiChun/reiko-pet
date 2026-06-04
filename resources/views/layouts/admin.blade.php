<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '管理後台') — 禮寵</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500&family=Noto+Serif+TC:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-cream min-h-screen flex">

{{-- Sidebar --}}
<aside id="sidebar"
       class="fixed top-0 left-0 h-full w-64 bg-ink z-50 flex flex-col transition-transform duration-300
              -translate-x-full lg:translate-x-0">
    <div class="px-8 py-8 border-b border-cream/10">
        <p class="font-serif text-2xl tracking-widest text-cream">禮寵</p>
        <p class="text-[10px] tracking-[0.35em] uppercase text-cream/30 mt-0.5">Admin Panel</p>
    </div>

    @php
    $navGroups = [
        ['label' => null, 'items' => [
            ['href' => route('admin.dashboard'), 'label' => '儀表板', 'route' => 'admin.dashboard'],
        ]],
        ['label' => '首頁管理', 'items' => [
            ['href' => route('admin.cms'),           'label' => '內容管理',  'route' => 'admin.cms'],
            ['href' => route('admin.announcements'), 'label' => '最新公告',  'route' => 'admin.announcements'],
            ['href' => route('admin.promotions'),    'label' => '活動優惠',  'route' => 'admin.promotions'],
            ['href' => route('admin.flyers'),        'label' => 'DM 傳單',   'route' => 'admin.flyers'],
            ['href' => route('admin.coupons'),       'label' => '優惠券管理','route' => 'admin.coupons'],
        ]],
        ['label' => '寵物美容', 'items' => [
            ['href' => route('admin.services'),     'label' => '美容服務管理', 'route' => 'admin.services'],
            ['href' => route('admin.addons'),        'label' => '加值服務管理', 'route' => 'admin.addons'],
            ['href' => route('admin.appointments'), 'label' => '美容預約管理', 'route' => 'admin.appointments'],
        ]],
        ['label' => '寵物住宿', 'items' => [
            ['href' => route('admin.accommodation.cms'),      'label' => '住宿內容管理', 'route' => 'admin.accommodation.cms'],
            ['href' => route('admin.accommodation.rooms'),    'label' => '住宿房型管理', 'route' => 'admin.accommodation.rooms'],
            ['href' => route('admin.accommodation.bookings'), 'label' => '住宿預約管理', 'route' => 'admin.accommodation.bookings'],
        ]],
        ['label' => '線上商城', 'items' => [
            ['href' => route('admin.products'), 'label' => '商品管理', 'route' => 'admin.products'],
            ['href' => route('admin.orders'),   'label' => '訂單管理', 'route' => 'admin.orders'],
        ]],
        ['label' => '寵物專欄', 'items' => [
            ['href' => route('admin.articles'), 'label' => '文章管理', 'route' => 'admin.articles'],
        ]],
        ['label' => '關於我們', 'items' => [
            ['href' => route('admin.stores'),  'label' => '門市管理',   'route' => 'admin.stores'],
            ['href' => route('admin.about'),   'label' => '內容管理',   'route' => 'admin.about'],
            ['href' => route('admin.survey'),  'label' => '問卷管理',   'route' => 'admin.survey'],
        ]],
        ['label' => '人事管理', 'items' => [
            ['href' => route('admin.members'), 'label' => '會員管理',   'route' => 'admin.members'],
            ['href' => route('admin.groomers'),'label' => '美容師管理', 'route' => 'admin.groomers'],
        ]],
    ];
    @endphp

    <nav class="flex-1 py-4 overflow-y-auto" id="admin-nav">
        @foreach($navGroups as $group)
        <div class="mb-1">
            @if($group['label'])
            <p class="px-8 pt-4 pb-1.5 text-[10px] tracking-[0.35em] uppercase text-cream/25 font-medium select-none">{{ $group['label'] }}</p>
            @endif
            @foreach($group['items'] as $item)
            <a href="{{ $item['href'] }}"
               data-nav
               class="flex items-center gap-3 px-8 py-2.5 text-sm tracking-widest transition-colors
                      {{ request()->routeIs($item['route']) ? 'text-cream bg-cream/10' : 'text-cream/50 hover:text-cream hover:bg-cream/5' }}">
                {{ $item['label'] }}
            </a>
            @endforeach
        </div>
        @endforeach
    </nav>

    <div class="px-8 py-6 border-t border-cream/10 space-y-3">
        <a href="{{ url('/') }}" target="_blank"
           class="flex items-center gap-2 text-xs text-cream/50 hover:text-cream tracking-widest transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
            </svg>
            前往前台
        </a>
        <p class="text-xs text-cream/30 tracking-wide">{{ Auth::user()->name }}</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs text-cream/40 hover:text-cream tracking-widest transition-colors">登出</button>
        </form>
    </div>
</aside>

{{-- Mobile overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

{{-- Main --}}
<div class="flex-1 lg:ml-64 flex flex-col min-h-screen">

    {{-- Top bar --}}
    <header class="bg-white border-b border-border px-6 lg:px-10 h-16 flex items-center justify-between sticky top-0 z-30">
        <button onclick="toggleSidebar()" class="lg:hidden text-ink">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
            </svg>
        </button>
        <h1 class="text-sm tracking-[0.3em] uppercase text-ink" id="admin-page-title">@yield('page-title', '管理後台')</h1>
        <a href="{{ url('/') }}" target="_blank"
           class="text-xs text-muted tracking-widest hover:text-accent transition-colors">前台 ↗</a>
    </header>

    {{-- Content --}}
    <main class="flex-1 p-6 lg:p-10" id="admin-content">

        @if(session('success'))
        <div class="mb-6 bg-accent/10 border border-accent/20 px-5 py-4 text-xs text-accent tracking-wide rounded" id="flash-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 border border-red-200 bg-red-50 px-5 py-4 text-xs text-red-600 tracking-wide rounded">
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.toggle('hidden');
}

// ── AJAX 導覽（Item 9）──
(function () {
    const contentEl = document.getElementById('admin-content');
    if (!contentEl) return;

    function loadPage(url) {
        fetch(url, { headers: { 'X-Admin-SPA': '1' } })
            .then(r => r.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('admin-content');
                const newTitle = doc.getElementById('admin-page-title');
                if (!newContent) { window.location.href = url; return; }
                contentEl.innerHTML = newContent.innerHTML;
                if (newTitle) document.getElementById('admin-page-title').textContent = newTitle.textContent;
                document.title = doc.title;
                history.pushState({ url }, '', url);
                // 重新執行頁面內的 script
                contentEl.querySelectorAll('script').forEach(old => {
                    const s = document.createElement('script');
                    s.textContent = old.textContent;
                    old.replaceWith(s);
                });
                // 更新側欄 active 狀態
                document.querySelectorAll('[data-nav]').forEach(a => {
                    const isActive = a.href === url || url.startsWith(a.href + '?');
                    a.classList.toggle('text-cream', isActive);
                    a.classList.toggle('bg-cream/10', isActive);
                    a.classList.toggle('text-cream/50', !isActive);
                });
                // 捲回頂端
                window.scrollTo(0, 0);
            })
            .catch(() => { window.location.href = url; });
    }

    document.addEventListener('click', function (e) {
        const a = e.target.closest('[data-nav]');
        if (!a) return;
        e.preventDefault();
        loadPage(a.href);
    });

    window.addEventListener('popstate', function (e) {
        if (e.state && e.state.url) loadPage(e.state.url);
    });
})();
</script>

@stack('scripts')
</body>
</html>
