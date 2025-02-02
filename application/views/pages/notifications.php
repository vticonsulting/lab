<main class="flex-1 bg-gray-100">
  <div class="bg-gray-100" style="min-height: 320px;">

	<div class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end">
		<div x-data="{ show: true }" x-show="show" x-description="Notification panel, show/hide based on alert state." x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-lg pointer-events-auto ring-1 ring-black ring-opacity-5">
		<div class="p-4">
			<div class="flex items-start">
			<div class="flex-shrink-0">
				<svg class="w-6 h-6 text-green-400" x-description="Heroicon name: check-circle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
	<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
	</svg>
			</div>
			<div class="ml-3 w-0 flex-1 pt-0.5">
				<p class="text-sm font-medium text-gray-900">
				Successfully saved!
				</p>
				<p class="mt-1 text-sm text-gray-500">
				Anyone with a link can now view this file.
				</p>
			</div>
			<div class="flex flex-shrink-0 ml-4">
				<button @click="show = false; setTimeout(() => show = true, 1000)" class="inline-flex text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
				<span class="sr-only">Close</span>
				<svg class="w-5 h-5" x-description="Heroicon name: x" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
	<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
	</svg>
				</button>
			</div>
			</div>
		</div>
		</div>
	</div>

  </div>
</main>
