<div class="" style="">
  <div class="bg-gray-100">
    <script>
      function radioGroup() {
        return {
          active: 1,
          onArrowUp(index) {
            this.select(this.active - 1 < 0 ? this.$refs.radiogroup.children.length - 1 : this.active - 1);
          },
          onArrowDown(index) {
            this.select(this.active + 1 > this.$refs.radiogroup.children.length - 1 ? 0 : this.active + 1);
          },
          select(index) {
            this.active = index;
          },
        };
      }
    </script>

  <header x-data="{ open: false }" class="bg-white shadow">
    <div class="px-2 mx-auto max-w-7xl sm:px-4 lg:divide-y lg:divide-gray-200 lg:px-8">
      <div class="relative flex justify-between h-16">
        <div class="relative z-10 flex px-2 lg:px-0">
          <div class="flex items-center flex-shrink-0">
            <img class="block w-auto h-8" src="https://tailwindui.com/img/logos/workflow-mark-orange-500.svg" alt="Workflow">
          </div>
        </div>
        <div class="relative z-0 flex items-center justify-center flex-1 px-2 sm:absolute sm:inset-0">
          <div class="w-full max-w-xs">
            <label for="search" class="sr-only">Search</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-400" x-description="Heroicon name: search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
</svg>
              </div>
              <input name="search" id="search" class="block w-full py-2 pl-10 pr-3 text-sm placeholder-gray-500 bg-white border border-gray-300 rounded-md focus:outline-none focus:text-gray-900 focus:placeholder-gray-400 focus:ring-1 focus:ring-gray-900 focus:border-gray-900 sm:text-sm" placeholder="Search" type="search">
            </div>
          </div>
        </div>
        <div class="relative z-10 flex items-center lg:hidden">
          <!-- Mobile menu button -->
          <button @click="open = !open" class="inline-flex items-center justify-center p-2 text-gray-400 rounded-md hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-gray-900" x-bind:aria-expanded="open">
            <span class="sr-only">Open menu</span>
            <!-- Icon when menu is closed. -->
            <svg x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'hidden': open, 'block': !open }" class="block w-6 h-6" x-description="Heroicon name: menu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
</svg>
            <!-- Icon when menu is open. -->
            <svg x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'hidden': !open, 'block': open }" class="hidden w-6 h-6" x-description="Heroicon name: x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
</svg>
          </button>
        </div>
        <div class="hidden lg:relative lg:z-10 lg:ml-4 lg:flex lg:items-center">
          <button class="flex-shrink-0 p-1 text-gray-400 bg-white rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
            <span class="sr-only">View notifications</span>
            <svg class="w-6 h-6" x-description="Heroicon name: bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
</svg>
          </button>

          <!-- Profile dropdown -->
          <div @click.away="open = false" class="relative flex-shrink-0 ml-4" x-data="{ open: false }">
            <div>
              <button @click="open = !open" class="flex bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900" id="user-menu" aria-haspopup="true" x-bind:aria-expanded="open">
                <span class="sr-only">Open user menu</span>
                <img class="w-8 h-8 rounded-full" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=80" alt="">
              </button>
            </div>
            <div x-show="open" x-description="Profile dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-48 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5" role="menu" aria-orientation="vertical" aria-labelledby="user-menu" style="display: none;">

                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your Profile</a>

                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>

                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Sign out</a>

            </div>
          </div>
        </div>
      </div>
      <nav class="hidden lg:py-2 lg:flex lg:space-x-8" aria-label="Global">

          <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:bg-gray-50 hover:text-gray-900">
            Dashboard
          </a>

          <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:bg-gray-50 hover:text-gray-900">
            Jobs
          </a>

          <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:bg-gray-50 hover:text-gray-900">
            Applicants
          </a>

          <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:bg-gray-50 hover:text-gray-900">
            Company
          </a>

      </nav>
    </div>

    <nav x-description="Mobile menu, toggle classes based on menu state." x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'block': open, 'hidden': !open }" class="hidden lg:hidden" aria-label="Global">
      <div class="px-2 pt-2 pb-3 space-y-1">

          <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 rounded-md hover:bg-gray-50 hover:text-gray-900">Dashboard</a>

          <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 rounded-md hover:bg-gray-50 hover:text-gray-900">Jobs</a>

          <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 rounded-md hover:bg-gray-50 hover:text-gray-900">Applicants</a>

          <a href="#" class="block px-3 py-2 text-base font-medium text-gray-900 rounded-md hover:bg-gray-50 hover:text-gray-900">Company</a>

      </div>
      <div class="pt-4 pb-3 border-t border-gray-200">
        <div class="flex items-center px-4">
          <div class="flex-shrink-0">
            <img class="w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=80" alt="">
          </div>
          <div class="ml-3">
            <div class="text-base font-medium text-gray-800">Lisa Marie</div>
            <div class="text-sm font-medium text-gray-500">lisamarie@example.com</div>
          </div>
          <button class="flex-shrink-0 p-1 ml-auto text-gray-400 bg-white rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
            <span class="sr-only">View notifications</span>
            <svg class="w-6 h-6" x-description="Heroicon name: bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
</svg>
          </button>
        </div>
        <div class="px-2 mt-3 space-y-1">

            <a href="#" class="block px-3 py-2 text-base font-medium text-gray-500 rounded-md hover:bg-gray-50 hover:text-gray-900">Your Profile</a>

            <a href="#" class="block px-3 py-2 text-base font-medium text-gray-500 rounded-md hover:bg-gray-50 hover:text-gray-900">Settings</a>

            <a href="#" class="block px-3 py-2 text-base font-medium text-gray-500 rounded-md hover:bg-gray-50 hover:text-gray-900">Sign out</a>

        </div>
      </div>
    </nav>
  </header>

  <main class="pb-10 mx-auto max-w-7xl lg:py-12 lg:px-8">
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
      <aside class="px-2 py-6 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
        <nav class="space-y-1">


              <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:text-gray-900 hover:bg-gray-50 group">
                <svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: user-circle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
</svg>
                <span class="truncate">
                  Profile
                </span>
              </a>


              <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:text-gray-900 hover:bg-gray-50 group">
                <svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: cog" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
</svg>
                <span class="truncate">
                  Account
                </span>
              </a>


              <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:text-gray-900 hover:bg-gray-50 group">
                <svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: key" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
</svg>
                <span class="truncate">
                  Password
                </span>
              </a>


              <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:text-gray-900 hover:bg-gray-50 group">
                <svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
</svg>
                <span class="truncate">
                  Notifications
                </span>
              </a>


              <!-- Current: "bg-gray-50 text-orange-600 hover:bg-white", Default: "text-gray-900 hover:text-gray-900 hover:bg-gray-50" -->
              <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-orange-600 rounded-md bg-gray-50 hover:bg-white group" aria-current="page">
                <!-- Current: "text-orange-500", Default: "text-gray-400 group-hover:text-gray-500" -->
                <svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-orange-500" x-description="Heroicon name: credit-card" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
</svg>
                <span class="truncate">
                  Plan &amp; Billing
                </span>
              </a>


              <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-md hover:text-gray-900 hover:bg-gray-50 group">
                <svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: view-grid-add" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
</svg>
                <span class="truncate">
                  Integrations
                </span>
              </a>

        </nav>
      </aside>

      <!-- Payment details -->
      <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
        <section aria-labelledby="payment_details_heading">
          <form action="#" method="POST">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
              <div class="px-4 py-6 bg-white sm:p-6">
                <div>
                  <h2 id="payment_details_heading" class="text-lg font-medium leading-6 text-gray-900">Payment details</h2>
                  <p class="mt-1 text-sm text-gray-500">Update your billing information. Please note that updating your location could affect your tax rates.</p>
                </div>

                <div class="grid grid-cols-4 gap-6 mt-6">
                  <div class="col-span-4 sm:col-span-2">
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First name</label>
                    <input type="text" name="first_name" id="first_name" autocomplete="cc-given-name" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
                  </div>

                  <div class="col-span-4 sm:col-span-2">
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
                    <input type="text" name="last_name" id="last_name" autocomplete="cc-family-name" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
                  </div>

                  <div class="col-span-4 sm:col-span-2">
                    <label for="email_address" class="block text-sm font-medium text-gray-700">Email address</label>
                    <input type="text" name="email_address" id="email_address" autocomplete="email" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
                  </div>

                  <div class="col-span-4 sm:col-span-1">
                    <label for="expiration_date" class="block text-sm font-medium text-gray-700">Expration date</label>
                    <input type="text" name="expiration_date" id="expiration_date" autocomplete="cc-exp" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm" placeholder="MM / YY">
                  </div>

                  <div class="col-span-4 sm:col-span-1">
                    <label for="security_code" class="flex items-center text-sm font-medium text-gray-700">
                      <span>Security code</span>
                      <svg class="flex-shrink-0 w-5 h-5 ml-1 text-gray-300" x-description="Heroicon name: question-mark-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
</svg>
                    </label>
                    <input type="text" name="security_code" id="security_code" autocomplete="cc-csc" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
                  </div>

                  <div class="col-span-4 sm:col-span-2">
                    <label for="country" class="block text-sm font-medium text-gray-700">Country / Region</label>
                    <select id="country" name="country" autocomplete="country" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
                      <option>United States</option>
                      <option>Canada</option>
                      <option>Mexico</option>
                    </select>
                  </div>

                  <div class="col-span-4 sm:col-span-2">
                    <label for="postal_code" class="block text-sm font-medium text-gray-700">ZIP / Postal</label>
                    <input type="text" name="postal_code" id="postal_code" autocomplete="postal-code" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
                  </div>
                </div>
              </div>
              <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
                <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-gray-800 border border-transparent rounded-md shadow-sm hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                  Save
                </button>
              </div>
            </div>
          </form>
        </section>

        <!-- Plan -->
        <section aria-labelledby="plan_heading">
          <form action="#" method="POST">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
              <div class="px-4 py-6 space-y-6 bg-white sm:p-6">
                <div>
                  <h2 id="plan_heading" class="text-lg font-medium leading-6 text-gray-900">Plan</h2>
                </div>

                <fieldset x-data="radioGroup()">
                  <legend class="sr-only">
                    Pricing plans
                  </legend>
                  <ul class="relative -space-y-px bg-white rounded-md" x-ref="radiogroup">


                        <li>
                          <div :class="{ 'border-gray-200': !(active === 0), 'bg-orange-50 border-orange-200 z-10': active === 0 }" class="relative z-10 flex flex-col p-4 border border-orange-200 rounded-tl-md rounded-tr-md md:pl-4 md:pr-6 md:grid md:grid-cols-3 bg-orange-50">
                            <label class="flex items-center text-sm cursor-pointer">
                              <input name="pricing_plan" type="radio" @click="select(0)" @keydown.space="select(0)" @keydown.arrow-up="onArrowUp(0)" @keydown.arrow-down="onArrowDown(0)" class="w-4 h-4 text-orange-500 border-gray-300 cursor-pointer focus:ring-gray-900" aria-describedby="plan-option-pricing-0 plan-option-limit-0">
                              <span class="ml-3 font-medium text-gray-900">Startup</span>
                            </label>
                            <p id="plan-option-pricing-0" class="pl-1 ml-6 text-sm md:ml-0 md:pl-0 md:text-center">
                              <span :class="{ 'text-orange-900': active === 0, 'text-gray-900': !(active === 0) }" class="font-medium text-orange-900">$29 / mo</span>
                              <span :class="{ 'text-orange-700': active === 0, 'text-gray-500': !(active === 0) }" class="text-orange-700">($290 / yr)</span>
                            </p>
                            <p id="plan-option-limit-0" :class="{ 'text-orange-700': active === 0, 'text-gray-500': !(active === 0) }" class="pl-1 ml-6 text-sm text-orange-700 md:ml-0 md:pl-0 md:text-right">Up to 5 active job postings</p>
                          </div>
                        </li>


                        <li>
                          <div :class="{ 'border-gray-200': !(active === 1), 'bg-orange-50 border-orange-200 z-10': active === 1 }" class="relative flex flex-col p-4 border border-gray-200 md:pl-4 md:pr-6 md:grid md:grid-cols-3">
                            <label class="flex items-center text-sm cursor-pointer">
                              <input name="pricing_plan" type="radio" @click="select(1)" @keydown.space="select(1)" @keydown.arrow-up="onArrowUp(1)" @keydown.arrow-down="onArrowDown(1)" class="w-4 h-4 text-orange-500 border-gray-300 cursor-pointer focus:ring-gray-900" aria-describedby="plan-option-pricing-1 plan-option-limit-1" checked="">
                              <span class="ml-3 font-medium text-gray-900">Business</span>
                            </label>
                            <p id="plan-option-pricing-1" class="pl-1 ml-6 text-sm md:ml-0 md:pl-0 md:text-center">
                              <span :class="{ 'text-orange-900': active === 1, 'text-gray-900': !(active === 1) }" class="font-medium text-gray-900">$99 / mo</span>
                              <span :class="{ 'text-orange-700': active === 1, 'text-gray-500': !(active === 1) }" class="text-gray-500">($990 / yr)</span>
                            </p>
                            <p id="plan-option-limit-1" :class="{ 'text-orange-700': active === 1, 'text-gray-500': !(active === 1) }" class="pl-1 ml-6 text-sm text-gray-500 md:ml-0 md:pl-0 md:text-right">Up to 25 active job postings</p>
                          </div>
                        </li>


                        <li>
                          <div :class="{ 'border-gray-200': !(active === 2), 'bg-orange-50 border-orange-200 z-10': active === 2 }" class="relative flex flex-col p-4 border border-gray-200 rounded-bl-md rounded-br-md md:pl-4 md:pr-6 md:grid md:grid-cols-3">
                            <label class="flex items-center text-sm cursor-pointer">
                              <input name="pricing_plan" type="radio" @click="select(2)" @keydown.space="select(2)" @keydown.arrow-up="onArrowUp(2)" @keydown.arrow-down="onArrowDown(2)" class="w-4 h-4 text-orange-500 border-gray-300 cursor-pointer focus:ring-gray-900" aria-describedby="plan-option-pricing-2 plan-option-limit-2">
                              <span class="ml-3 font-medium text-gray-900">Enterprise</span>
                            </label>
                            <p id="plan-option-pricing-2" class="pl-1 ml-6 text-sm md:ml-0 md:pl-0 md:text-center">
                              <span :class="{ 'text-orange-900': active === 2, 'text-gray-900': !(active === 2) }" class="font-medium text-gray-900">$249 / mo</span>
                              <span :class="{ 'text-orange-700': active === 2, 'text-gray-500': !(active === 2) }" class="text-gray-500">($2490 / yr)</span>
                            </p>
                            <p id="plan-option-limit-2" :class="{ 'text-orange-700': active === 2, 'text-gray-500': !(active === 2) }" class="pl-1 ml-6 text-sm text-gray-500 md:ml-0 md:pl-0 md:text-right">Unlimited active job postings</p>
                          </div>
                        </li>

                  </ul>
                </fieldset>

                <div class="flex items-center">
                  <button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="true" aria-labelledby="toggleLabel" x-data="{ on: true }" :class="{ 'bg-gray-200': !on, 'bg-orange-500': on }" class="relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out bg-orange-500 border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                    <span class="sr-only">Use setting</span>
                    <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-5 bg-white rounded-full shadow ring-0"></span>
                  </button>
                  <span id="toggleLabel" class="ml-3">
                    <span class="text-sm font-medium text-gray-900">Annual billing </span>
                    <span class="text-sm text-gray-500">(Save 10%)</span>
                  </span>
                </div>
              </div>
              <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
                <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-gray-800 border border-transparent rounded-md shadow-sm hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                  Save
                </button>
              </div>
            </div>
          </form>
        </section>

        <!-- Billing history -->
        <section aria-labelledby="billing_history_heading">
          <div class="pt-6 bg-white shadow sm:rounded-md sm:overflow-hidden">
            <div class="px-4 sm:px-6">
              <h2 id="billing_history_heading" class="text-lg font-medium leading-6 text-gray-900">Billing history</h2>
            </div>
            <div class="flex flex-col mt-6">
              <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                  <div class="overflow-hidden border-t border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Date
                          </th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Description
                          </th>
                          <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Amount
                          </th>
                          <!--
                            `relative` is added here due to a weird bug in Safari that causes `sr-only` headings to introduce overflow on the body on mobile.
                          -->
                          <th scope="col" class="relative px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            <span class="sr-only">View receipt</span>
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200" x-max="1">

                          <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                              1/1/2020
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              Business Plan - Annual Billing
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              CA$109.00
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                              <a href="#" class="text-orange-600 hover:text-orange-900">View receipt</a>
                            </td>
                          </tr>

                          <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                              1/1/2019
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              Business Plan - Annual Billing
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              CA$109.00
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                              <a href="#" class="text-orange-600 hover:text-orange-900">View receipt</a>
                            </td>
                          </tr>

                          <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                              1/1/2018
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              Business Plan - Annual Billing
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              CA$109.00
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                              <a href="#" class="text-orange-600 hover:text-orange-900">View receipt</a>
                            </td>
                          </tr>

                          <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                              1/1/2017
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              Business Plan - Annual Billing
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                              CA$109.00
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                              <a href="#" class="text-orange-600 hover:text-orange-900">View receipt</a>
                            </td>
                          </tr>

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </main>

  </div>
</div>
