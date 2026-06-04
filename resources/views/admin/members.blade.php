@extends('layouts.admin')
@section('title', '會員管理')
@section('page-title', 'Members')

@section('content')

<div class="bg-white border border-border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-border">
                <tr class="text-[11px] tracking-[0.3em] uppercase text-muted">
                    <th class="text-left px-6 py-4">姓名</th>
                    <th class="text-left px-6 py-4">Email</th>
                    <th class="text-left px-6 py-4">手機</th>
                    <th class="text-left px-6 py-4">身份</th>
                    <th class="text-left px-6 py-4">寵物數</th>
                    <th class="text-left px-6 py-4">預約數</th>
                    <th class="text-left px-6 py-4">加入時間</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($members as $member)
                <tr class="hover:bg-cream/50 transition-colors">
                    <td class="px-6 py-4 text-ink tracking-wide">{{ $member->name }}</td>
                    <td class="px-6 py-4 text-muted tracking-wide">@maskEmail($member->email)</td>
                    <td class="px-6 py-4 text-muted tracking-wide">{{ $member->phone ? \App\Helpers\MaskHelper::phone($member->phone) : '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] tracking-widest border px-2.5 py-1
                                     {{ $member->role->value === 'admin' ? 'text-accent bg-accent/10 border-accent/20'
                                        : ($member->role->value === 'groomer' ? 'text-amber-600 bg-amber-50 border-amber-200'
                                                                              : 'text-muted bg-cream border-border') }}">
                            {{ ['admin'=>'管理員','groomer'=>'美容師','customer'=>'會員'][$member->role->value] ?? $member->role->value }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-muted text-center">{{ $member->pets_count ?? 0 }}</td>
                    <td class="px-6 py-4 text-muted text-center">{{ $member->appointments_count ?? 0 }}</td>
                    <td class="px-6 py-4 text-muted tracking-wide">{{ $member->created_at->format('Y/m/d') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-muted tracking-widest">無會員資料</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($members->hasPages())
<div class="mt-8">{{ $members->links() }}</div>
@endif

@endsection
