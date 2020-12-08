<main class="flex-1 p-8">
	<div x-data="{open: false}" class="relative">
		<!-- Trigger element -->
		<button @mouseover="open = true" @mouseleave="open = false" class="px-4 py-2 text-sm text-gray-600 transition duration-150 bg-gray-400 rounded shadow-sm hover:bg-gray-500 hover:text-gray-800">
			Hover to reveal all
		</button>

		<!-- Popover -->
		<!-- Make sure to add the requisite CSS for x-cloak: https://github.com/alpinejs/alpine#x-cloak -->

		<div x-cloak x-show.transition="open" id="popover" class="absolute z-20 flex flex-col max-w-xl p-3 mt-3 space-y-1 text-sm text-gray-600 bg-white rounded shadow-2xl">
			<strong class="text-sm font-semibold text-gray-800">What is this about?</strong>
			<p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa laborum incidunt debitis necessitatibus veritatis.</p>
		</div>

	</div>

	<!-- CSS for the arrow pseudoelement, not possible natively with Tailwind. Probably move this to your stylesheets. If you can find a Tailwind solution, please let me know! -->
	<style>
		#popover:before {
			content: "";
			position: absolute;
			bottom: 100%;
			left: 5%;
			margin-left: -10px;
			border-width: 7px;
			border-style: solid;
			border-color: transparent transparent lightgray transparent;
		}
	</style>
</main>
