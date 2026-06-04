<div class="flex gap-0 border-b border-border mb-12 overflow-x-auto">
    @foreach([
        ['href' => route('member.index'),            'label' => '我的總覽',  'route' => 'member.index'],
        ['href' => route('member.profile'),          'label' => '個人資料',  'route' => 'member.profile'],
        ['href' => route('member.pets'),             'label' => '我的寵物',  'route' => 'member.pets'],
        ['href' => route('member.appointments'),     'label' => '預約記錄',  'route' => 'member.appointments'],
        ['href' => route('member.accommodation'),    'label' => '住宿紀錄',  'route' => 'member.accommodation'],
        ['href' => route('member.orders'),           'label' => '商城訂單',  'route' => 'member.orders'],
        ['href' => route('member.bookmarks'),        'label' => '收藏文章',  'route' => 'member.bookmarks'],
        ['href' => route('member.product-bookmarks'),'label' => '收藏商品',  'route' => 'member.product-bookmarks'],
    ] as $tab)
    <a href="{{ $tab['href'] }}"
       class="px-5 py-3 text-sm tracking-widest transition-colors border-b-2 whitespace-nowrap
              {{ request()->routeIs($tab['route'])
                 ? 'border-accent text-accent'
                 : 'border-transparent text-muted hover:text-ink' }}">
        {{ $tab['label'] }}
    </a>
    @endforeach
</div>
