<x-guest-layout>
    <p>{{ __('Admin Dashboard') }} — {{ auth('admin')->user()->nama }}</p>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <x-primary-button class="mt-4">
            {{ __('Log out') }}
        </x-primary-button>
    </form>
</x-guest-layout>
