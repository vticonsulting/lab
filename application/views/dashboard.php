<main class="flex-1">
	<header class="p-8 bg-gray-800">
		<div class="mx-auto max-w-7xl">
			<!-- page title and page actions container -->
			<div class="mt-2 md:flex md:items-center md:justify-between">

				<!-- page title container -->
				<div class="flex-1 min-w-0">
					<!-- Page Title -->
					<h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl sm:truncate">

						<!-- Gradient Text Effect -->
						<span class="text-transparent bg-gradient-to-r bg-clip-text from-purple-400 to-primary-500">
							<?= $title ?>
						</span>
					</h2>
				</div>

				<!-- page actions container-->
				<div class="flex mt-4 lex-shrink-0 md:mt-0 md:ml-4">
					<button type="button" class="inline-flex items-center px-6 py-3 text-base font-medium text-white border border-transparent rounded-md shadow-sm bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
						Quick Links
						<svg class="w-5 h-5 ml-3 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
					</button>
				</div>
			</div>
		</div>
	</header>
</main>
