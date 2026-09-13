@php
    $tesRoute = Auth::user()->materiSelesaiSemua()
        ? route('kuesioner.posttest.create')
        : route('kuesioner.pretest.create');

    $items = [
        ['label' => 'Home', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'icon' => 'home'],
        ['label' => 'Materi', 'href' => route('modul.index'), 'active' => request()->routeIs('modul.*'), 'icon' => 'book'],
        ['label' => 'Tes', 'href' => $tesRoute, 'active' => request()->routeIs('kuesioner.*'), 'icon' => 'clipboard'],
        ['label' => 'Haid', 'href' => route('kalender-haid.index'), 'active' => request()->routeIs('kalender-haid.*'), 'icon' => 'calendar'],
        ['label' => 'Profil', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.*'), 'icon' => 'user'],
    ];

    $paths = [
        'home' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/>',
        'book' => '<path d="M12 6.5C10.5 5 8.5 4.5 4 4.5V19c4.5 0 6.5.5 8 2 1.5-1.5 3.5-2 8-2V4.5c-4.5 0-6.5.5-8 2Z"/><path d="M12 6.5V21"/>',
        'clipboard' => '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1"/><path d="M9 12.5l2 2 4-4"/>',
        'calendar' => '<rect x="4" y="5" width="16" height="16" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/><path d="M12 18c-1.6-1.2-2.8-2.2-2.8-3.4a1.6 1.6 0 0 1 2.8-1 1.6 1.6 0 0 1 2.8 1c0 1.2-1.2 2.2-2.8 3.4Z"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4.5 21a7.5 7.5 0 0 1 15 0"/>',
    ];
@endphp

{{-- Chrome-nya menempel di <ul>, bukan di <nav>: di HP bar selebar layar seperti
     biasa, di desktop menyusut jadi pil mengambang selebar panel — bukan bar penuh
     dengan lima ikon menggerombol di tengah layar. --}}
<nav class="fixed inset-x-0 bottom-0 z-20 flex justify-center">
    <ul class="flex w-full max-w-md items-stretch justify-between border-t border-brand-line bg-brand-paper px-2 pb-[env(safe-area-inset-bottom)]
               sm:mb-4 sm:rounded-full sm:border sm:px-3 sm:shadow-lg sm:ring-1 sm:ring-black/5">
        @foreach ($items as $item)
            <li class="flex-1">
                <a href="{{ $item['href'] }}"
                   @if ($item['active']) aria-current="page" @endif
                   class="flex min-h-[56px] flex-col items-center justify-center gap-1 rounded-xl py-2 text-[11px] {{ $item['active'] ? 'font-semibold text-brand-forest' : 'text-brand-ink/60' }}">
                    <span class="flex h-8 w-12 items-center justify-center rounded-full {{ $item['active'] ? 'bg-brand-mint-soft' : '' }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            {!! $paths[$item['icon']] !!}
                        </svg>
                    </span>
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
