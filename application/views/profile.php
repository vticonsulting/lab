<main class="flex-1 p-8">
	<header>
		<div>
			<nav class="sm:hidden" aria-label="Back">
				<a href="#" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
					<!-- Heroicon name: chevron-left -->
					<svg class="flex-shrink-0 w-5 h-5 mr-1 -ml-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
						<path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
					</svg>
					Back
				</a>
			</nav>

			<nav class="hidden sm:flex" aria-label="Breadcrumb">
				<ol class="flex items-center space-x-4">
					<li>
						<div>
							<a href="<?= base_url() ?>" class="text-sm font-medium text-gray-500 hover:text-gray-700">
								Home
							</a>
						</div>
					</li>
					<li>
						<div class="flex items-center">
							<!-- Heroicon name: chevron-right -->
							<svg class="flex-shrink-0 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
							</svg>

							<a href="#" aria-current="page" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
								<?= $title ?>
							</a>
						</div>
					</li>
				</ol>
			</nav>
		</div>

		<div class="mt-2 md:flex md:items-center md:justify-between">
			<div class="flex-1 min-w-0">
				<h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
					<?= $title; ?>
				</h2>
			</div>
		</div>
	</header>
</main>
