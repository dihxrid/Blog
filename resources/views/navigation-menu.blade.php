<nav class="flex items-center justify-between px-6 py-3 border-b border-gray-100">
    <div id="nav-left" class="flex items-center">
        <a href="{{ route('home') }}">
            <x-application-mark />
        </a>
        <div class="ml-10 top-menu">
            <div class="flex space-x-4">
                <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                    {{ __('menu.home') }}
                </x-nav-link>
                <x-nav-link href="{{ route('posts.index') }}" :active="request()->routeIs('posts.index')">
                    {{ __('menu.blog') }}
                </x-nav-link>
            </div>
        </div>
    </div>
    <div id="nav-right" class="flex items-center space-x-4 md:space-x-6">
        <div class="flex space-x-4">
            @foreach (config('app.supported_locales') as $locale => $data)
                <a href="{{ route('locale', $locale) }}" class="flex items-center">
                    <x-dynamic-component :component="'flag-country-' . $data['icon']" class="w-6 h-6" />
                </a>
            @endforeach
        </div>
        
        <div class="flex items-center">
            @auth
                @include('layouts.partials.header-right-auth')
            @else
                @include('layouts.partials.header-right-guest')
            @endauth
        </div>
    </div>
    
</nav>
