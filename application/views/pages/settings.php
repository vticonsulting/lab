<div class="bg-gray-100">
	<div x-data="{ open: false }" class="relative pb-32 overflow-hidden bg-light-blue-700">
		<nav :class="{ 'bg-light-blue-900': open, 'bg-transparent': !open }" class="relative z-10 bg-transparent border-b border-teal-500 border-opacity-25 lg:bg-transparent lg:border-none">
			<div class="px-2 mx-auto max-w-7xl sm:px-4 lg:px-8">
				<div class="relative flex items-center justify-between h-16 lg:border-b lg:border-light-blue-800">
					<div class="flex items-center px-2 lg:px-0">
						<div class="flex-shrink-0">
							<img class="block w-auto h-8" src="https://tailwindui.com/img/logos/workflow-mark-teal-400.svg" alt="Workflow">
						</div>
						<div class="hidden lg:block lg:ml-6 lg:space-x-4">
							<div class="flex">
								<!-- Current: "bg-black bg-opacity-25", Default: "hover:bg-light-blue-800" -->
								<a href="#" class="px-3 py-2 text-sm font-medium text-white bg-black bg-opacity-25 rounded-md" aria-current="page">Dashboard</a>
								<a href="#" class="px-3 py-2 text-sm font-medium text-white rounded-md hover:bg-light-blue-800">Jobs</a>
								<a href="#" class="px-3 py-2 text-sm font-medium text-white rounded-md hover:bg-light-blue-800">Applicants</a>
								<a href="#" class="px-3 py-2 text-sm font-medium text-white rounded-md hover:bg-light-blue-800">Company</a>
							</div>
						</div>
					</div>
					<div class="flex justify-center flex-1 px-2 lg:ml-6 lg:justify-end">
						<div class="w-full max-w-lg lg:max-w-xs">
							<label for="search" class="sr-only">Search</label>
							<div class="relative text-light-blue-100 focus-within:text-gray-400">
								<div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
									<svg class="flex-shrink-0 w-5 h-5" x-description="Heroicon name: search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
										<path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
									</svg>
								</div>
								<input id="search" name="search" class="block w-full py-2 pl-10 pr-3 leading-5 bg-opacity-50 border border-transparent rounded-md bg-light-blue-700 placeholder-light-blue-100 focus:outline-none focus:bg-white focus:ring-white focus:border-white focus:placeholder-gray-500 focus:text-gray-900 sm:text-sm" placeholder="Search" type="search">
							</div>
						</div>
					</div>
					<div class="flex lg:hidden">
						<!-- Mobile menu button -->
						<button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-light-blue-200 hover:text-white hover:bg-light-blue-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" x-bind:aria-expanded="open">
							<span class="sr-only">Open main menu</span>
							<!-- Icon when menu is closed. -->
							<svg x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'hidden': open, 'block': !open }" class="flex-shrink-0 block w-6 h-6" x-description="Heroicon name: menu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
							</svg>
							<!-- Icon when menu is open. -->
							<svg x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'hidden': !open, 'block': open }" class="flex-shrink-0 hidden w-6 h-6" x-description="Heroicon name: x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
							</svg>
						</button>
					</div>
					<div class="hidden lg:block lg:ml-4">
						<div class="flex items-center">
							<button class="flex-shrink-0 p-1 rounded-full text-light-blue-200 hover:bg-light-blue-800 hover:text-white focus:outline-none focus:bg-light-blue-900 focus:ring-2 focus:ring-offset-2 focus:ring-offset-light-blue-900 focus:ring-white">
								<span class="sr-only">View notifications</span>
								<svg class="w-6 h-6" x-description="Heroicon name: bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
								</svg>
							</button>

							<!-- Profile dropdown -->
							<div @click.away="open = false" class="relative flex-shrink-0 ml-4" x-data="{ open: false }">
								<div>
									<button @click="open = !open" class="flex text-sm text-white rounded-full focus:outline-none focus:bg-light-blue-900 focus:ring-2 focus:ring-offset-2 focus:ring-offset-light-blue-900 focus:ring-white" id="user-menu" aria-haspopup="true" x-bind:aria-expanded="open">
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
				</div>
			</div>

			<div x-description="Mobile menu, toggle classes based on menu state." x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'block': open, 'hidden': !open }" class="hidden bg-light-blue-900 lg:hidden">
				<div class="px-2 pt-2 pb-3 space-y-1">


					<!-- Current: "bg-black bg-opacity-25", Default: "hover:bg-light-blue-800" -->
					<a href="#" class="block px-3 py-2 text-base font-medium text-white bg-black bg-opacity-25 rounded-md" aria-current="page">Dashboard</a>


					<a href="#" class="block px-3 py-2 text-base font-medium text-white rounded-md hover:bg-light-blue-800">Jobs</a>


					<a href="#" class="block px-3 py-2 text-base font-medium text-white rounded-md hover:bg-light-blue-800">Applicants</a>


					<a href="#" class="block px-3 py-2 text-base font-medium text-white rounded-md hover:bg-light-blue-800">Company</a>

				</div>
				<div class="pt-4 pb-3 border-t border-light-blue-800">
					<div class="flex items-center px-4">
						<div class="flex-shrink-0">
							<img class="w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=80h" alt="">
						</div>
						<div class="ml-3">
							<div class="text-base font-medium text-white">Debbie Lewis</div>
							<div class="text-sm font-medium text-light-blue-200">debbielewis@example.com</div>
						</div>
						<button class="flex-shrink-0 p-1 ml-auto rounded-full text-light-blue-200 hover:bg-light-blue-800 hover:text-white focus:outline-none focus:bg-light-blue-900 focus:ring-2 focus:ring-offset-2 focus:ring-offset-light-blue-900 focus:ring-white">
							<span class="sr-only">View notifications</span>
							<svg class="w-6 h-6" x-description="Heroicon name: bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
							</svg>
						</button>
					</div>
					<div class="px-2 mt-3">

						<a href="#" class="block px-3 py-2 text-base font-medium rounded-md text-light-blue-200 hover:text-white hover:bg-light-blue-800">Your Profile</a>

						<a href="#" class="block px-3 py-2 text-base font-medium rounded-md text-light-blue-200 hover:text-white hover:bg-light-blue-800">Settings</a>

						<a href="#" class="block px-3 py-2 text-base font-medium rounded-md text-light-blue-200 hover:text-white hover:bg-light-blue-800">Sign out</a>

					</div>
				</div>
			</div>
		</nav>
		<div :class="{ 'bottom-0': open, 'inset-y-0': !open }" class="absolute inset-x-0 inset-y-0 flex justify-center w-full overflow-hidden transform -translate-x-1/2 left-1/2 lg:inset-y-0" aria-hidden="true">
			<div class="flex-grow bg-opacity-75 bg-light-blue-900"></div>
			<svg class="flex-shrink-0" width="1750" height="308" viewBox="0 0 1750 308" xmlns="http://www.w3.org/2000/svg">
				<path opacity=".75" d="M1465.84 308L16.816 0H1750v308h-284.16z" fill="#075985"></path>
				<path opacity=".75" d="M1733.19 0L284.161 308H0V0h1733.19z" fill="#0c4a6e"></path>
			</svg>
			<div class="flex-grow bg-opacity-75 bg-light-blue-800"></div>
		</div>
		<header class="relative py-10">
			<div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
				<h1 class="text-3xl font-bold text-white">
					Settings
				</h1>
			</div>
		</header>
	</div>

	<main class="relative -mt-32">
		<div class="max-w-screen-xl px-4 pb-6 mx-auto sm:px-6 lg:pb-16 lg:px-8">
			<div class="overflow-hidden bg-white rounded-lg shadow">
				<div class="divide-y divide-gray-200 lg:grid lg:grid-cols-12 lg:divide-y-0 lg:divide-x">
					<aside class="py-6 lg:col-span-3">
						<nav>


							<!-- Current: "bg-teal-50 border-teal-500 text-teal-700 hover:bg-teal-50 hover:text-teal-700", Default: "border-transparent text-gray-900 hover:bg-gray-50 hover:text-gray-900" -->
							<a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-teal-700 border-l-4 border-teal-500 bg-teal-50 hover:bg-teal-50 hover:text-teal-700 group" aria-current="page">
								<!-- Current: "text-teal-500 group-hover:text-teal-500", Default: "text-gray-400 group-hover:text-gray-500" -->
								<svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-teal-500 group-hover:text-teal-500" x-description="Heroicon name: user-circle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
								</svg>
								<span class="truncate">
									Profile
								</span>
							</a>


							<a href="#" class="flex items-center px-3 py-2 mt-1 text-sm font-medium text-gray-900 border-l-4 border-transparent hover:bg-gray-50 hover:text-gray-900 group">
								<svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: cog" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
								</svg>
								<span class="truncate">
									Account
								</span>
							</a>


							<a href="#" class="flex items-center px-3 py-2 mt-1 text-sm font-medium text-gray-900 border-l-4 border-transparent hover:bg-gray-50 hover:text-gray-900 group">
								<svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: key" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
								</svg>
								<span class="truncate">
									Password
								</span>
							</a>


							<a href="#" class="flex items-center px-3 py-2 mt-1 text-sm font-medium text-gray-900 border-l-4 border-transparent hover:bg-gray-50 hover:text-gray-900 group">
								<svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
								</svg>
								<span class="truncate">
									Notifications
								</span>
							</a>


							<a href="#" class="flex items-center px-3 py-2 mt-1 text-sm font-medium text-gray-900 border-l-4 border-transparent hover:bg-gray-50 hover:text-gray-900 group">
								<svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: credit-card" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
								</svg>
								<span class="truncate">
									Billing
								</span>
							</a>


							<a href="#" class="flex items-center px-3 py-2 mt-1 text-sm font-medium text-gray-900 border-l-4 border-transparent hover:bg-gray-50 hover:text-gray-900 group">
								<svg class="flex-shrink-0 w-6 h-6 mr-3 -ml-1 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: view-grid-add" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
								</svg>
								<span class="truncate">
									Integrations
								</span>
							</a>

						</nav>
					</aside>

					<form class="divide-y divide-gray-200 lg:col-span-9" action="#" method="POST">
						<!-- Profile section -->
						<div class="px-4 py-6 sm:p-6 lg:pb-8">
							<div>
								<h2 class="text-lg font-medium leading-6 text-gray-900">Profile</h2>
								<p class="mt-1 text-sm text-gray-500">
									This information will be displayed publicly so be careful what you share.
								</p>
							</div>

							<div class="flex flex-col mt-6 lg:flex-row">
								<div class="flex-grow space-y-6">
									<div>
										<label for="username" class="block text-sm font-medium text-gray-700">
											Username
										</label>
										<div class="flex mt-1 rounded-md shadow-sm">
											<span class="inline-flex items-center px-3 text-gray-500 border border-r-0 border-gray-300 bg-gray-50 rounded-l-md sm:text-sm">
												workcation.com/
											</span>
											<input type="text" name="username" id="username" autocomplete="username" class="flex-grow block w-full min-w-0 border-gray-300 rounded-none focus:ring-light-blue-500 focus:border-light-blue-500 rounded-r-md sm:text-sm" value="lisamarie">
										</div>
									</div>

									<div>
										<label for="about" class="block text-sm font-medium text-gray-700">
											About
										</label>
										<div class="mt-1">
											<textarea id="about" name="about" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm"></textarea>
										</div>
										<p class="mt-2 text-sm text-gray-500">
											Brief description for your profile. URLs are hyperlinked.
										</p>
									</div>
								</div>

								<div class="flex-grow mt-6 lg:mt-0 lg:ml-6 lg:flex-grow-0 lg:flex-shrink-0">
									<p class="text-sm font-medium text-gray-700" aria-hidden="true">
										Photo
									</p>
									<div class="mt-1 lg:hidden">
										<div class="flex items-center">
											<div class="flex-shrink-0 inline-block w-12 h-12 overflow-hidden rounded-full" aria-hidden="true">
												<img class="w-full h-full rounded-full" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=256&amp;h=256&amp;q=80h" alt="">
											</div>
											<div class="ml-5 rounded-md shadow-sm">
												<div class="relative flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md group hover:bg-gray-50 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-light-blue-500">
													<label for="user_photo" class="relative text-sm font-medium leading-4 text-gray-700 pointer-events-none">
														<span>Change</span>
														<span class="sr-only"> user photo</span>
													</label>
													<input id="user_photo" name="user_photo" type="file" class="absolute w-full h-full border-gray-300 rounded-md opacity-0 cursor-pointer">
												</div>
											</div>
										</div>
									</div>

									<div class="relative hidden overflow-hidden rounded-full lg:block">
										<img class="relative w-40 h-40 rounded-full" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=320&amp;h=320&amp;q=80" alt="">
										<label for="user-photo" class="absolute inset-0 flex items-center justify-center w-full h-full text-sm font-medium text-white bg-black bg-opacity-75 opacity-0 hover:opacity-100 focus-within:opacity-100">
											<span>Change</span>
											<span class="sr-only"> user photo</span>
											<input type="file" id="user-photo" name="user-photo" class="absolute inset-0 w-full h-full border-gray-300 rounded-md opacity-0 cursor-pointer">
										</label>
									</div>
								</div>
							</div>

							<div class="grid grid-cols-12 gap-6 mt-6">
								<div class="col-span-12 sm:col-span-6">
									<label for="first_name" class="block text-sm font-medium text-gray-700">First name</label>
									<input type="text" name="first_name" id="first_name" autocomplete="given-name" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm">
								</div>

								<div class="col-span-12 sm:col-span-6">
									<label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
									<input type="text" name="last_name" id="last_name" autocomplete="family-name" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm">
								</div>

								<div class="col-span-12">
									<label for="url" class="block text-sm font-medium text-gray-700">URL</label>
									<input type="text" name="url" id="url" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm">
								</div>

								<div class="col-span-12 sm:col-span-6">
									<label for="company" class="block text-sm font-medium text-gray-700">Company</label>
									<input type="text" name="company" id="company" autocomplete="organization" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm">
								</div>
							</div>
						</div>

						<!-- Privacy section -->
						<div class="pt-6 divide-y divide-gray-200">
							<div class="px-4 sm:px-6">
								<div>
									<h2 class="text-lg font-medium leading-6 text-gray-900">Privacy</h2>
									<p class="mt-1 text-sm text-gray-500">
										Ornare eu a volutpat eget vulputate. Fringilla commodo amet.
									</p>
								</div>
								<ul class="mt-2 divide-y divide-gray-200">
									<li class="flex items-center justify-between py-4">
										<div class="flex flex-col">
											<p id="privacy-option-label-1" class="text-sm font-medium text-gray-900">
												Available to hire
											</p>
											<p id="privacy-option-description-1" class="text-sm text-gray-500">
												Nulla amet tempus sit accumsan. Aliquet turpis sed sit lacinia.
											</p>
										</div>
										<button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="true" aria-labelledby="privacy-option-label-1" aria-describedby="privacy-option-description-1" x-data="{ on: true }" :class="{ 'bg-gray-200': !on, 'bg-teal-500': on }" class="relative inline-flex flex-shrink-0 h-6 ml-4 transition-colors duration-200 ease-in-out bg-teal-500 border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-blue-500">
											<span class="sr-only">Use setting</span>
											<span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-5 bg-white rounded-full shadow ring-0"></span>
										</button>
									</li>
									<li class="flex items-center justify-between py-4">
										<div class="flex flex-col">
											<p id="privacy-option-label-2" class="text-sm font-medium text-gray-900">
												Make account private
											</p>
											<p id="privacy-option-description-2" class="text-sm text-gray-500">
												Pharetra morbi dui mi mattis tellus sollicitudin cursus pharetra.
											</p>
										</div>
										<button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="false" aria-labelledby="privacy-option-label-2" aria-describedby="privacy-option-description-2" x-data="{ on: false }" :class="{ 'bg-gray-200': !on, 'bg-teal-500': on }" class="relative inline-flex flex-shrink-0 h-6 ml-4 transition-colors duration-200 ease-in-out bg-gray-200 border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-blue-500">
											<span class="sr-only">Use setting</span>
											<span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-0 bg-white rounded-full shadow ring-0"></span>
										</button>
									</li>
									<li class="flex items-center justify-between py-4">
										<div class="flex flex-col">
											<p id="privacy-option-label-3" class="text-sm font-medium text-gray-900">
												Allow commenting
											</p>
											<p id="privacy-option-description-3" class="text-sm text-gray-500">
												Integer amet, nunc hendrerit adipiscing nam. Elementum ame
											</p>
										</div>
										<button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="true" aria-labelledby="privacy-option-label-3" aria-describedby="privacy-option-description-3" x-data="{ on: true }" :class="{ 'bg-gray-200': !on, 'bg-teal-500': on }" class="relative inline-flex flex-shrink-0 h-6 ml-4 transition-colors duration-200 ease-in-out bg-teal-500 border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-blue-500">
											<span class="sr-only">Use setting</span>
											<span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-5 bg-white rounded-full shadow ring-0"></span>
										</button>
									</li>
									<li class="flex items-center justify-between py-4">
										<div class="flex flex-col">
											<p id="privacy-option-label-4" class="text-sm font-medium text-gray-900">
												Allow mentions
											</p>
											<p id="privacy-option-description-4" class="text-sm text-gray-500">
												Adipiscing est venenatis enim molestie commodo eu gravid
											</p>
										</div>
										<button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="true" aria-labelledby="privacy-option-label-4" aria-describedby="privacy-option-description-4" x-data="{ on: true }" :class="{ 'bg-gray-200': !on, 'bg-teal-500': on }" class="relative inline-flex flex-shrink-0 h-6 ml-4 transition-colors duration-200 ease-in-out bg-teal-500 border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-blue-500">
											<span class="sr-only">Use setting</span>
											<span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-5 bg-white rounded-full shadow ring-0"></span>
										</button>
									</li>
								</ul>
							</div>
							<div class="flex justify-end px-4 py-4 mt-4 sm:px-6">
								<button type="button" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-blue-500">
									Cancel
								</button>
								<button type="submit" class="inline-flex justify-center px-4 py-2 ml-5 text-sm font-medium text-white border border-transparent rounded-md shadow-sm bg-light-blue-700 hover:bg-light-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-blue-500">
									Save
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</main>
</div>