<main class="flex-1">
	<header class="p-8 bg-primary-900">
		<div class="lg:flex lg:items-center lg:justify-between">
			<div class="flex-1 min-w-0">
				<h2 class="text-2xl font-bold leading-7 text-primary-50 sm:text-3xl sm:truncate">
					<?= $title ?>
				</h2>
				<div class="flex flex-col mt-1 sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
					<div class="flex items-center mt-2 text-sm text-gray-500">
						<!-- Heroicon name: briefcase -->
						<svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
							<path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
							<path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
						</svg>
						Full-time
					</div>
					<div class="flex items-center mt-2 text-sm text-gray-500">
						<!-- Heroicon name: location-marker -->
						<svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
							<path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
						</svg>
						Remote
					</div>
					<div class="flex items-center mt-2 text-sm text-gray-500">
						<!-- Heroicon name: currency-dollar -->
						<svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
							<path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
						</svg>
						$120k &ndash; $140k
					</div>
				<div class="flex items-center mt-2 text-sm text-gray-500">
						<!-- Heroicon name: calendar -->
						<svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
							<path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
						</svg>
						Closing on January 9, 2020
					</div>
				</div>
			</div>
			<div class="flex mt-5 lg:mt-0 lg:ml-4">
				<span class="sm:ml-3">
					<button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
						<svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
						</svg>
						Add New
					</button>
				</span>

				<!-- Dropdown -->
				<span class="relative ml-3 sm:hidden">
					<div class="w-64 mx-auto">

						<div x-data="{ open: true }" @keydown.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
							<div>
								<button @click="open = !open" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-primary-500" id="options-menu" aria-haspopup="true" aria-expanded="true" x-bind:aria-expanded="open">
									Options
									<svg class="w-5 h-5 ml-2 -mr-1" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
										<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
									</svg>
								</button>
							</div>

							<div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
								<div class="py-1">
									<a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 group hover:bg-gray-100 hover:text-gray-900" role="menuitem">
										<svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: pencil-alt" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
											<path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
											<path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
										</svg>
										Edit
									</a>
									<a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 group hover:bg-gray-100 hover:text-gray-900" role="menuitem">
										<svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: duplicate" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
											<path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z"></path>
											<path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z"></path>
										</svg>
										Duplicate
									</a>
								</div>
								<div class="py-1">
									<a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 group hover:bg-gray-100 hover:text-gray-900" role="menuitem">
										<svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: archive" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
											<path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
											<path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
										</svg>
										Archive
									</a>
									<a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 group hover:bg-gray-100 hover:text-gray-900" role="menuitem">
										<svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: arrow-circle-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
											<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
										</svg>
										Move
									</a>
								</div>
								<div class="py-1">
									<a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 group hover:bg-gray-100 hover:text-gray-900" role="menuitem">
										<svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: user-add" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
											<path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
										</svg>
										Share
									</a>
									<a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 group hover:bg-gray-100 hover:text-gray-900" role="menuitem">
										<svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: heart" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
											<path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
										</svg>
										Add to favorites
									</a>
								</div>
								<div class="py-1">
									<a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 group hover:bg-gray-100 hover:text-gray-900" role="menuitem">
										<svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500" x-description="Heroicon name: trash" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
											<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
										</svg>
										Delete
									</a>
								</div>
							</div>
						</div>

					</div>
				</span>
			</div>
		</div>
	</header>

	<div class="flex flex-col mt-8">
	<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
		<div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
		<div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
			<table class="min-w-full divide-y divide-gray-200">
			<thead class="bg-gray-50">
				<tr>
				<th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
					Name
				</th>
				<th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
					Title
				</th>
				<th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
					Email
				</th>
				<th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
					Role
				</th>
				<th scope="col" class="relative px-6 py-3">
					<span class="sr-only">Edit</span>
				</th>
				</tr>
			</thead>
			<tbody>
				<!-- Odd row -->
				<tr class="bg-white">
				<td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
					Jane Cooper
				</td>
				<td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
					Regional Paradigm Technician
				</td>
				<td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
					jane.cooper@example.com
				</td>
				<td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
					Admin
				</td>
				<td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
					<a href="#" class="text-primary-600 hover:text-primary-900">Edit</a>
				</td>
				</tr>

				<!-- Even row -->
				<tr class="bg-gray-50">
				<td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
					Cody Fisher
				</td>
				<td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
					Product Directives Officer
				</td>
				<td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
					cody.fisher@example.com
				</td>
				<td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
					Owner
				</td>
				<td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
					<a href="#" class="text-primary-600 hover:text-primary-900">Edit</a>
				</td>
				</tr>

				<!-- More rows... -->
			</tbody>
			</table>
		</div>
		</div>
	</div>
	</div>
</main>
