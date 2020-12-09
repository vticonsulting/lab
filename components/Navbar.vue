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
            <svg
              class="w-8 h-8 text-primary-500"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M16.7198 20.1C16.8531 18.4666 17.3198 16.3 17.9198 14.5333C18.4864 12.9666 18.8531 11.6 18.7864 11.5C18.4531 11.2 15.5864 17.8 15.1864 19.7666L14.7864 21.8L14.0198 20.6333C12.0198 17.7666 12.4531 14.9 15.2531 12.1333C16.7198 10.7 20.5864 8.1333 21.3198 8.1333C21.3864 8.1333 21.4531 9.2333 21.4864 10.5333C21.4864 11.8666 21.6198 14.4666 21.7531 16.3C21.9198 18.5 21.8864 19.9333 21.6531 20.5333C21.1864 21.7666 19.4198 22.8 17.8198 22.8H16.5198L16.7198 20.1Z"
                fill="currentColor"
              />
              <path
                d="M8.01988 21.1665C1.58655 18.9332 0.21988 12.3665 4.35321 3.4665L5.65321 0.666504L6.61988 2.3665C7.18655 3.33317 9.01988 5.3665 10.9199 7.13317L14.2199 10.1998L12.9532 11.8665C11.6199 13.6665 10.9532 15.3665 10.9532 17.1665C10.9199 18.2665 10.9199 18.2665 10.4199 17.6332C9.45321 16.3998 7.71988 12.6665 6.78655 9.8665C6.28655 8.33317 5.78655 7.1665 5.71988 7.2665C5.41988 7.5665 6.98655 14.2665 7.95321 16.7332C8.45321 18.0665 9.28655 19.6665 9.75321 20.2998C11.0865 22.0665 10.8865 22.1665 8.01988 21.1665Z"
                fill="currentColor"
              />
            </svg>
            <span class="ml-2 font-mono" style="font-size: 16px">&lt;Lab /></span>
          </NuxtLink>

          <!-- Navbar_links -->
          <div class="hidden md:ml-6 md:flex md:items-center md:space-x-2">
            <!-- <div
              :key="`group-${i}`"
              v-for="(group, i) in $store.state.navigationGroups"
              class="relative inline-block text-left"
            >
              <div>
                <button
                  @click="open = !open"
                  type="button"
                  class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent whitespace-nowrap hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-primary-500"
                  id="options-menu"
                  aria-haspopup="true"
                  x-bind:aria-expanded="open"
                  aria-expanded="true"
                >
                  {{ group.label }}
                  <svg
                    class="w-5 h-5 ml-2 -mr-1"
                    x-description="Heroicon name: chevron-down"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>
              </div>

              <div
                x-show="open"
                x-description="Dropdown panel, show/hide based on dropdown state."
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute left-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
              >
                <div
                  class="py-1"
                  role="menu"
                  aria-orientation="vertical"
                  aria-labelledby="options-menu"
                >
                  <NuxtLink
                    :key="`link-${j}`"
                    v-for="(link, j) in group.links"
                    :to="localePath(link.path)"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                    role="menuitem"
                  >
                    {{ link.label }}
                  </NuxtLink>
                </div>
              </div>
            </div> -->

            <NuxtLink
              class="flex items-center space-x-2"
              :to="link.path"
              :key="index"
              v-for="(link, index) in $store.state.site.navGroups"
            >
              {{ $t(link.label) }}
            </NuxtLink>
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
            <ColorModeToggle />
          </div>
        </div>
      </div>
    </div>

    <div class="hidden lg:hidden">
      <div class="px-2 pt-2 pb-3">
        <a
          href="#"
          class="block px-3 py-2 text-base font-medium text-white transition duration-150 ease-in-out bg-gray-900 rounded-md focus:outline-none focus:text-white focus:bg-gray-700"
        >
          {{ $t('Dashboard') }}
        </a>
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
