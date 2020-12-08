<main class="flex-1 p-8 text-gray-800 bg-gray-200">
	<section class="container mx-auto bg-white rounded shadow">
		<div class="container p-4 mx-auto mb-4 bg-gray-100" x-data="{ tab: 'tab1' }">
			<h2 class="text-2xl font-bold">Tabs</h2>
			<ul class="flex mt-6 border-b">
				<li class="mr-1 -mb-px">
					<a class="inline-block px-4 py-2 font-semibold rounded-t hover:text-blue-800" :class="{ 'bg-white text-blue-700 border-l border-t border-r' : tab === 'tab1' }" href="#" @click.prevent="tab = 'tab1'">Tab 1</a>
				</li>
				<li class="mr-1 -mb-px">
					<a class="inline-block px-4 py-2 font-semibold text-blue-500 hover:text-blue-800" :class="{ 'bg-white text-blue-700 border-l border-t border-r' : tab === 'tab2' }" href="#" @click.prevent="tab = 'tab2'">Tab 2</a>
				</li>
				<li class="mr-1 -mb-px">
					<a class="inline-block px-4 py-2 font-semibold text-blue-500 hover:text-blue-800" :class="{ 'bg-white text-blue-700 border-l border-t border-r' : tab === 'tab3' }" href="#" @click.prevent="tab = 'tab3'">Tab 3</a>
				</li>
			</ul>
			<div class="px-4 py-4 pt-4 bg-white border-b border-l border-r content">
				<div x-show="tab === 'tab1'">
					Tab1 content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt sunt, consectetur eos quod perferendis mollitia consequuntur natus, porro molestiae qui iusto deserunt rerum tempore voluptatum itaque. Ad, nisi esse cum quidem consequuntur ullam obcaecati.
				</div>
				<div x-show="tab === 'tab2'">
					Tab2 content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt sunt, consectetur eos quod perferendis mollitia consequuntur natus, porro molestiae qui iusto deserunt rerum tempore voluptatum itaque. Ad, nisi esse cum quidem consequuntur ullam obcaecati.
				</div>
				<div x-show="tab === 'tab3'">
					Tab3 content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt sunt, consectetur eos quod perferendis mollitia consequuntur natus, porro molestiae qui iusto deserunt rerum tempore voluptatum itaque. Ad, nisi esse cum quidem consequuntur ullam obcaecati.
				</div>

			</div>
		</div>
	</section>


	<section class="container p-16 mx-auto mt-16 bg-white rounded shadow">
		<!--
  This example requires Tailwind CSS v2.0+

  This example requires some changes to your config:

  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ]
  }
  ```
-->
		<div>
			<div class="sm:hidden">
				<label for="tabs" class="sr-only">Select a tab</label>
				<select id="tabs" name="tabs" class="block w-full border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
					<option>My Account</option>
					<option>Company</option>
					<option selected>Team Members</option>
					<option>Billing</option>
				</select>
			</div>
			<div class="hidden sm:block">
				<div class="border-b border-gray-200">
					<nav class="flex -mb-px space-x-8" aria-label="Tabs">
						<a href="#" class="inline-flex items-center px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 group">
							<!-- Heroicon name: user -->
							<svg class="text-gray-400 group-hover:text-gray-500 -ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
							</svg>
							<span>My Account</span>
						</a>
						<a href="#" class="inline-flex items-center px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 group">
							<!-- Heroicon name: office-building -->
							<svg class="text-gray-400 group-hover:text-gray-500 -ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
							</svg>
							<span>Company</span>
						</a>
						<!-- Current: "border-primary-500 text-primary-600", Default: "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" -->
						<a href="#" class="inline-flex items-center px-1 py-4 text-sm font-medium text-primary-600 border-b-2 border-primary-500 group" aria-current="page">
							<!-- Current: "text-primary-500", Default: "text-gray-400 group-hover:text-gray-500" -->
							<!-- Heroicon name: users -->
							<svg class="text-primary-500 -ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
							</svg>
							<span>Team Members</span>
						</a>
						<a href="#" class="inline-flex items-center px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 group">
							<!-- Heroicon name: credit-card -->
							<svg class="text-gray-400 group-hover:text-gray-500 -ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
								<path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
							</svg>
							<span>Billing</span>
						</a>
					</nav>
				</div>
			</div>
		</div>

	</section>

	<section class="container p-16 mx-auto mt-16 bg-white rounded shadow">
		<div class="lg:hidden">
			<label for="selected-tab" class="sr-only">Select a tab</label>
			<select id="selected-tab" name="selected-tab" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
				<option selected="">General</option>
				<option>Password</option>
				<option>Notifications</option>
				<option>Plan</option>
				<option>Billing</option>
				<option>Team Members</option>
			</select>
		</div>
		<div class="hidden lg:block">
			<div class="border-b border-gray-200">
				<nav class="flex -mb-px">
					<!-- Current: "border-purple-500 text-purple-600", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
					<a href="#" class="px-1 py-4 text-sm font-medium text-purple-600 border-b-2 border-purple-500 whitespace-nowrap">
						General
					</a>
					<a href="#" class="px-1 py-4 ml-8 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:text-gray-700 whitespace-nowrap">
						Password
					</a>
					<a href="#" class="px-1 py-4 ml-8 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:text-gray-700 whitespace-nowrap">
						Notifications
					</a>
					<a href="#" class="px-1 py-4 ml-8 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:text-gray-700 whitespace-nowrap">
						Plan
					</a>
					<a href="#" class="px-1 py-4 ml-8 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:text-gray-700 whitespace-nowrap">
						Billing
					</a>
					<a href="#" class="px-1 py-4 ml-8 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:text-gray-700 whitespace-nowrap">
						Team Members
					</a>
				</nav>
			</div>
		</div>
	</section>
</main>
