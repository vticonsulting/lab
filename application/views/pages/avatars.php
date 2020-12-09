<main class="flex-1 p-8">
	<article class="flex mt-8 space-x-2">
		<!-- Initials Avatar -->
		<span class="inline-flex items-center justify-center bg-gray-500 rounded-full h-14 w-14">
			<span class="text-xl font-medium leading-none text-white">TW</span>
		</span>

		<!-- Victor's Avatar -->
		<span class="relative inline-block">
			<img class="rounded-full h-14 w-14" src="<?= base_url('assets/img/avatars/victor.jpg') ?>" alt="">
			<span class="absolute top-0 right-0 block h-3.5 w-3.5 rounded-full ring-2 ring-white bg-green-400"></span>
		</span>

		<!-- Avatar With Dropdown -->
		<div x-data="{ open: false }" class="relative z-10">
			<button @click="open = true" class="relative inline-block">
				<img class="rounded-full h-14 w-14" src="<?= base_url('assets/img/avatars/victor.jpg') ?>" alt="">
				<span class="absolute top-0 right-0 block h-3.5 w-3.5 rounded-full ring-2 ring-white bg-green-400"></span>
			</button>
			<div x-cloak x-show.transition="open" @click.away="open = false" @keydown.escape.window="open = false" class="absolute z-10 mt-2 overflow-hidden bg-white rounded-sm shadow-lg">
				<nav>
					<ul class="py-2 text-sm text-gray-600">
						<li class="flex">
							<a class="w-full px-6 py-2 transition duration-150 hover:text-primary-600 hover:underline hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-primary-600 focus:underline" href="" target="_blank" title="Link 1">Link 1</a>
						</li>
						<li class="flex">
							<a class="w-full px-6 py-2 transition duration-150 hover:text-primary-600 hover:underline hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-primary-600 focus:underline" href="" target="_blank" title="Link 2">Link 2</a>
						</li>
						<li class="flex">
							<a class="w-full px-6 py-2 transition duration-150 hover:text-primary-600 hover:underline hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-primary-600 focus:underline" href="" target="_blank" title="Link 3">Link 3</a>
						</li>
						<li class="flex">
							<a class="w-full px-6 py-2 transition duration-150 hover:text-primary-600 hover:underline hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-primary-600 focus:underline" href="" target="_blank" title="Link 4">Link 4</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>

		<span class="relative inline-block">
			<img class="rounded-full h-14 w-14" src="<?= base_url('assets/img/avatars/jeremy.png') ?>" alt="">
			<span class="absolute top-0 right-0 block h-3.5 w-3.5 rounded-full ring-2 ring-white bg-red-400"></span>
		</span>

		<span class="inline-block overflow-hidden bg-gray-100 rounded-full h-14 w-14">
			<svg class="w-full h-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
				<path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path>
			</svg>
		</span>



	</article>
</main>
