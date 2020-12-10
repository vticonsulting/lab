<template>
  <nav class="text-gray-800 bg-gray-100 dark:bg-gray-800 dark:text-gray-100">
    <div class="px-2 mx-auto sm:px-4 lg:px-8">
      <div class="relative flex items-center justify-between h-16">
        <div class="flex items-center px-2 lg:px-0">
          <!-- Navbar Brand -->
          <NuxtLink
            to="/"
            class="flex items-center flex-shrink-0 font-mono text-xl font-bold text-gray-800 dark:text-white"
          >
            <SiteLogo :name="$store.state.site.name.toLowerCase()" />

            <span ref="site-name" class="ml-2 font-mono" style="font-size: 16px">
              &lt;{{ $store.state.site.name }} />
            </span>
          </NuxtLink>

          <!-- Navbar_links -->
          <div class="hidden md:ml-6 md:flex md:items-center md:space-x-2">
            <NavbarLink
              :link="link"
              :key="index"
              v-for="(link, index) in $store.state.site.navbar.links"
            >
              {{ $t(link.label) }}
            </NavbarLink>
          </div>
        </div>

        <!-- Mobile Menu Button -->
        <div class="flex lg:hidden">
          <!-- Mobile menu button -->
          <button
            class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white"
            aria-label="Main menu"
            aria-expanded="false"
          >
            <!-- Icon when menu is closed. -->
            <!--
            Heroicon name: menu

            Menu open: "hidden", Menu closed: "block"
          -->
            <svg
              class="block w-6 h-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
            <!-- Icon when menu is open. -->
            <!--
            Heroicon name: x

            Menu open: "block", Menu closed: "hidden"
          -->
            <svg
              class="hidden w-6 h-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <!-- Navbar Right -->
        <div class="hidden lg:block lg:ml-4">
          <div class="flex items-center space-x-8">
            <NuxtLink to="about">About</NuxtLink>

            <!-- <ColorModeToggle /> -->
          </div>
        </div>
      </div>
    </div>

    <div class="hidden lg:hidden">
      <div class="px-2 pt-2 pb-3">
        <NavbarLink />

        <a
          href="#"
          class="block px-3 py-2 mt-1 text-base font-medium text-gray-300 transition duration-150 ease-in-out rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
        >
          {{ $t('Team') }}
        </a>
        <a
          href="#"
          class="block px-3 py-2 mt-1 text-base font-medium text-gray-300 transition duration-150 ease-in-out rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
        >
          {{ $t('Projects') }}
        </a>
        <NuxtLink
          :to="localePath('calendar')"
          class="block px-3 py-2 mt-1 text-base font-medium text-gray-300 transition duration-150 ease-in-out rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
        >
          {{ $t('Calendar') }}
        </NuxtLink>
      </div>

      <div class="pt-4 pb-3 border-t border-gray-700">
        <div class="flex items-center px-5">
          <div class="flex-shrink-0">
            <img class="w-10 h-10 rounded-full" src="/img/avatars/jeremy.jpg" alt="" />
          </div>
          <div class="ml-3">
            <div class="text-base font-medium leading-6 text-white">Jeremy Doublestein</div>
            <div class="text-sm font-medium leading-5 text-gray-400">jeremy@promiserves.org</div>
          </div>
        </div>

        <div class="px-2 mt-3">
          <a
            href="#"
            class="block px-3 py-2 text-base font-medium text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
          >
            {{ $t('My Profile') }}
          </a>
          <a
            href="#"
            class="block px-3 py-2 mt-1 text-base font-medium text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
          >
            {{ $t('Settings') }}
          </a>
          <a
            href="#"
            class="block px-3 py-2 mt-1 text-base font-medium text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
          >
            {{ $t('Sign out') }}
          </a>
        </div>
      </div>
    </div>
  </nav>
</template>
