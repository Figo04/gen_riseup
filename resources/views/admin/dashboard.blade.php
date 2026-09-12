<x-admin-layout>
    <x-slot name="header">Dashboard</x-slot>

    <p class="text-gray-600">Selamat datang, {{ auth('admin')->user()->nama }}.</p>
</x-admin-layout>
