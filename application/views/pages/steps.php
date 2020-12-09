<main class="flex-1 p-8">
	<div class="container mx-auto">
		<!-- This example requires Tailwind CSS v2.0+ -->
		<nav aria-label="Progress">
			<ol class="space-y-4 md:flex md:space-y-0 md:space-x-8">
				<li class="md:flex-1">
					<!-- Completed Step -->
					<a href="#" class="flex flex-col py-2 pl-4 border-l-4 border-primary-600 group hover:border-primary-800 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4">
						<span class="text-xs font-semibold text-primary-600 uppercase group-hover:text-primary-800">Step 1</span>
						<span class="text-sm font-medium">Job details</span>
					</a>
				</li>

				<li class="md:flex-1">
					<!-- Current Step -->
					<a href="#" class="flex flex-col py-2 pl-4 border-l-4 border-primary-600 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4" aria-current="step">
						<span class="text-xs font-semibold text-primary-600 uppercase">Step 2</span>
						<span class="text-sm font-medium">Application form</span>
					</a>
				</li>

				<li class="md:flex-1">
					<!-- Upcoming Step -->
					<a href="#" class="flex flex-col py-2 pl-4 border-l-4 border-gray-200 group hover:border-gray-300 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4">
						<span class="text-xs font-semibold text-gray-500 uppercase group-hover:text-gray-700">Step 3</span>
						<span class="text-sm font-medium">Preview</span>
					</a>
				</li>
			</ol>
		</nav>
	</div>

	<section class="container mx-auto mt-16">
	<!-- This example requires Tailwind CSS v2.0+ -->
	<nav aria-label="Progress">
	<ol class="border border-gray-300 divide-y divide-gray-300 rounded-md md:flex md:divide-y-0">
		<li class="relative md:flex-1 md:flex">
		<!-- Completed Step -->
		<a href="#" class="flex items-center w-full group">
			<span class="flex items-center px-6 py-4 text-sm font-medium">
			<span class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-primary-600 rounded-full group-hover:bg-primary-800">
				<!-- Heroicon name: check -->
				<svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
				<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
				</svg>
			</span>
			<span class="ml-4 text-sm font-medium text-gray-900">Job details</span>
			</span>
		</a>

		<div class="absolute top-0 right-0 hidden w-5 h-full md:block" aria-hidden="true">
			<svg class="w-full h-full text-gray-300" viewBox="0 0 22 80" fill="none" preserveAspectRatio="none">
			<path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor" stroke-linejoin="round" />
			</svg>
		</div>
		</li>

		<li class="relative md:flex-1 md:flex">
		<!-- Current Step -->
		<a href="#" class="flex items-center px-6 py-4 text-sm font-medium">
			<span class="flex items-center justify-center flex-shrink-0 w-10 h-10 border-2 border-primary-600 rounded-full" aria-current="step">
			<span class="text-primary-600">02</span>
			</span>
			<span class="ml-4 text-sm font-medium text-primary-600">Application form</span>
		</a>

		<div class="absolute top-0 right-0 hidden w-5 h-full md:block" aria-hidden="true">
			<svg class="w-full h-full text-gray-300" viewBox="0 0 22 80" fill="none" preserveAspectRatio="none">
			<path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor" stroke-linejoin="round" />
			</svg>
		</div>
		</li>

		<li class="relative md:flex-1 md:flex">
		<!-- Upcoming Step -->
		<a href="#" class="flex items-center group">
			<span class="flex items-center px-6 py-4 text-sm font-medium">
			<span class="flex items-center justify-center flex-shrink-0 w-10 h-10 border-2 border-gray-300 rounded-full group-hover:border-gray-400">
				<span class="text-gray-500 group-hover:text-gray-900">03</span>
			</span>
			<span class="ml-4 text-sm font-medium text-gray-500 group-hover:text-gray-900">Preview</span>
			</span>
		</a>
		</li>
	</ol>
	</nav>
	</span>
</main>
