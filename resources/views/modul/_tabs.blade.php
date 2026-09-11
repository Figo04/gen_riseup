@php($activeClass = 'border-b-2 border-brand-ink font-semibold text-gray-800')
@php($inactiveClass = 'text-gray-500')

<nav class="flex gap-6 border-b">
    <a href="{{ route('modul.sub-bagian.show', [$modul, $subBagian]) }}" class="pb-2 {{ $aktif === 'materi' ? $activeClass : $inactiveClass }}">Materi</a>
    <a href="{{ route('modul.sub-bagian.refleksi', [$modul, $subBagian]) }}" class="pb-2 {{ $aktif === 'refleksi' ? $activeClass : $inactiveClass }}">Refleksi</a>
</nav>
