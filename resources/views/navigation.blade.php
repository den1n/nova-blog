@canany(['blogManager', 'blogViewPosts', 'blogViewCategories', 'blogViewTags'])
    <h3 class="flex items-center font-normal text-white mb-6 text-base no-underline">
        <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="2 2 20 20">
            <path fill="var(--sidebar-icon)" d="M18 21H7a4 4 0 0 1-4-4V5c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2h2a2 2 0 0 1 2 2v11a3 3 0 0 1-3 3zm-3-3V5H5v12c0 1.1.9 2 2 2h8.17a3 3 0 0 1-.17-1zm-7-3h4a1 1 0 0 1 0 2H8a1 1 0 0 1 0-2zm0-4h4a1 1 0 0 1 0 2H8a1 1 0 0 1 0-2zm0-4h4a1 1 0 0 1 0 2H8a1 1 0 1 1 0-2zm9 11a1 1 0 0 0 2 0V7h-2v11z"/>
        </svg>
        <span class="sidebar-label">
            {{ __('Blog') }}
        </span>
    </h3>

    <ul class="list-reset mb-8">
        @canany(['blogManager', 'blogViewPosts'])
            <li class="leading-wide mb-4 text-sm">
                <router-link :to="{
                    name: 'index',
                    params: {
                        resourceName: '{{ $postUriKey }}'
                    }
                }" class="text-white ml-8 no-underline dim">
                    {{ __('Posts') }}
                </router-link>
            </li>
        @endcanany
        @canany(['blogManager', 'blogViewCategories'])
        <li class="leading-wide mb-4 text-sm">
            <router-link :to="{
                name: 'index',
                params: {
                    resourceName: '{{ $categoryUriKey }}'
                }
            }" class="text-white ml-8 no-underline dim">
                {{ __('Categories') }}
            </router-link>
        </li>
        @endcanany
        @canany(['blogManager', 'blogViewTags'])
        <li class="leading-wide mb-4 text-sm">
            <router-link :to="{
                name: 'index',
                params: {
                    resourceName: '{{ $tagUriKey }}'
                }
            }" class="text-white ml-8 no-underline dim">
                {{ __('Tags') }}
            </router-link>
        </li>
        @endcanany
    </ul>
@endcanany
