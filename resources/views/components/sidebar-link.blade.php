<a href="{{ route($route) }}"
   class="sidebar-link {{ $active ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
    <x-icon :name="$icon" class="w-5 h-5" />
    {{ $slot }}
</a>