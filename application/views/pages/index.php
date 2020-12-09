
<main class="flex-1 p-8">
	<nav class="grid grid-flow-row-dense grid-cols-2 grid-rows-3 gap-4 auto-cols-min">
		<a class="flex items-center justify-center p-8 border rounded shadow" href="<?= base_url('index') ?>">
			Dashboard
		</a>

		<a class="flex items-center justify-center p-8 border rounded shadow" href="<?= base_url('posts') ?>">
			Blog
		</a>

		<a class="flex items-center justify-center p-8 border rounded shadow" href="<?= base_url('resources') ?>">
			Resources
		</a>

		<a class="flex items-center justify-center p-8 border rounded shadow" href="<?= base_url('categories') ?>">
			Categories
		</a>

		<a class="flex items-center justify-center p-8 border rounded shadow" href="<?= base_url('properties') ?>">
			Properties
		</a>
	</nav>

	<section class="mt-8 prose">
		<button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="true" x-data="{ on: false }" :class="{ 'bg-gray-200': !on, 'bg-primary-600': on }" class="relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out bg-primary-600 border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
			<span class="sr-only">Use setting</span>
			<span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-5 bg-white rounded-full shadow ring-0"></span>
		</button>


		<div class="flex items-center">
			<button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="false" aria-labelledby="toggleLabel" x-data="{ on: false }" :class="{ 'bg-gray-200': !on, 'bg-primary-600': on }" class="relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out bg-gray-200 border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
				<span class="sr-only">Use setting</span>
				<span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-0 bg-white rounded-full shadow ring-0"></span>
			</button>
			<span class="ml-3" id="toggleLabel">
				<span class="text-sm font-medium text-gray-900">Annual billing </span>
				<span class="text-sm text-gray-500">(Save 10%)</span>
			</span>
		</div>

		<div class="w-full max-w-xl mt-16 -mx-auto">

			<div class="flex items-center justify-between">
				<span class="flex flex-col flex-grow" id="toggleLabel">
					<span class="text-sm font-medium text-gray-900">Available to hire</span>
					<span class="text-sm leading-normal text-gray-500">Nulla amet tempus sit accumsan. Aliquet turpis sed sit lacinia.</span>
				</span>
				<button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="false" aria-labelledby="toggleLabel" x-data="{ on: false }" :class="{ 'bg-gray-200': !on, 'bg-primary-600': on }" class="relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out bg-gray-200 border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
					<span class="sr-only">Use setting</span>
					<span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-0 bg-white rounded-full shadow ring-0"></span>
				</button>
			</div>

		</div>





		<table>
			<tr>
				<td>PS-101 ema: Create new dashboard views</td>
			<tr>
			<tr>
				<td>PS-102 ema: Create new header navigation</td>
			</tr>
		</table>

		<section>
			<ul>
				<li>Upcoming Items</li>
				<li>Calendar</li>
				<li>Learning Modules</li>
				<li>Cases</li>
			</ul>
		</section>

	</section>

	<section class="mt-8 prose">
		<table>
			<tbody>
				<tr>
					<th>Projects</th>
					<td>
						<a href="https://tolbert.atlassian.net">
							tolbert.atlassian.net
						</a>
					</td>
					<td>
						<a href="https://promiseserves.atlassian.net/">
							promiserves.atlassian.net
						</a>
					</td>
				</tr>
				<tr>
					<th>Dashboard</th>
					<td>
						<a href="https://tolbert.atlassian.net/secure/Dashboard.jspa?selectPageId=10000">
							tolbert.atlassian.net
						</a>
					</td>
					<td>
						<a href="https://promiseserves.atlassian.net/secure/Dashboard.jspa?selectPageId=10000">
							promiserves.atlassian.net
						</a>
					</td>
				</tr>

				<tr>
					<th>Short Code</th>
					<td>
						<a href="https://tolbert.atlassian.net/jira/software/projects/TD/boards/1">
							TD
						</a>
					</td>
					<td>
						<a href="https://promiseserves.atlassian.net/jira/software/c/projects/PS/issues/?filter=myopenissues">
							My Open Issues
						</a>
					</td>
				</tr>
				<tr>
					<th>Code Repositories</th>
					<td>
						<a href="https://promiseserves.atlassian.net/secure/BrowseProjects.jspa?=&selectedProjectType=software">
							/secure/BrowseProjects.jspa?=&selectedProjectType=software
						</a>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<a href="https://promiseserves.atlassian.net/secure/RapidBoard.jspa?rapidView=1&projectKey=PS">
							/secure/RapidBoard.jspa?rapidView=1&projectKey=PS
						</a>
					</td>
				</tr>
			</tbody>
		</table>

		<a href="/changes-for-v2" class="ml-2 font-medium text-gray-500 hover:text-gray-900">Learn more →</a>

	</section>

	<section class="mt-8">
		<div class="overflow-hidden bg-white rounded-lg shadow">
			<header class="flex items-center justify-between px-4 py-5 space-x-2 bg-gray-100 border-b border-gray-200 sm:px-6">
				<h3 class="text-xl font-semibold">Header</h3>

				<span class="relative z-10 inline-flex rounded-md shadow-sm">
					<button type="button" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
						Save changes
					</button>
					<span x-data="{ open: false }" class="relative block -ml-px">
						<button @click="open = !open" @click.away="open = false" id="option-menu" type="button" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
							<span class="sr-only">Open options</span>
							<svg class="w-5 h-5" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
							</svg>
						</button>
						<div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-56 mt-2 -mr-1 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5" style="display: none;">
							<div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="option-menu">

								<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
									Save and schedule
								</a>

								<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
									Save and publish
								</a>

								<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
									Export PDF
								</a>
							</div>
						</div>
					</span>
				</span>
			</header>
			<div class="px-4 py-5 sm:p-6">
				<a href="<?= base_url() ?>" class="flex-shrink-0 block group">
					<div class="flex items-center">
						<div>
							<img class="inline-block rounded-full h-9 w-9" src="<?= base_url('assets/img/avatars/jeremy.png') ?>" alt="">
						</div>
						<div class="ml-3">
							<p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
								Jeremy Doublestein
							</p>
							<p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">
								View profile
							</p>
						</div>
					</div>
				</a>
			</div>
			<footer class="px-4 py-4 border-t border-gray-200 sm:px-6">
				Footer
			</footer>
		</div>
	</section>


	<section class="mt-8 prose">
		<h3>Access Levels</h3>

		<table>
			<tr>
				<td>Advocate</td>
				<td> 99 </td>
			</tr>
			<tr>
				<td>Staff</td>
				<td> 99 </td>
			</tr>
			<tr>
				<td>Administrator</td>
				<td> 99 </td>
			</tr>
			<tr>
				<td>Super Administrator</td>
				<td> 99 </td>
			</tr>
		</table>
	</section>

	<section class="mt-8 prose">
		<h3>Abilities</h3>
		<table>
			<tr>
				<td>Edit Church Profile</td>
			</tr>
			<tr>
				<td>Edit Church Profile</td>
			</tr>
		</table>
	</section>

	<section class="mt-8">
		<ul>
			<li class="flex items-center space-x-2">
				<svg width="24" height="24" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
				</svg>
				<span>Group</span>
			</li>
			<li class="flex items-center space-x-2">
				<svg width="24" height="24" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
				</svg>
				<span>Project</span>
			</li>
			<li class="flex items-center space-x-2">
				<svg width="24" height="24" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
				</svg>
				<span>My organization</span>
				<small>Any logged in user on <b>tolbert.atlassian.net</b></small>
			</li>
			<li class="flex items-center space-x-2">
				<svg width="24" height="24" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
				</svg>
				<span>Private</span>
				<small>Only you</b></small>
			</li>
		</ul>
	</section>


	<section class="mt-8">
		<div class="flex items-center space-x-2">
			<span>Your department</span>
		</div>
		<div class="flex items-center space-x-2">
			<svg style="color: var(--color-primary)" width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
			</svg>
			<span>Location</span>
		</div>
		<div class="flex items-center space-x-2">
			<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
			</svg>
			<span>victor@tolbert.design</span>
		</div>
	</section>
</main>
