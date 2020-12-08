<main class="flex-1 bg-gray-400">
	<div class="container px-4 mx-auto mt-12 mb-12 xl:px-64">
		<h2 class="text-2xl font-bold">Modal</h2>

		<div x-data="{ isOpen: false }">
			<button
				class="px-4 py-3 mt-4 text-sm text-white bg-blue-700 rounded"
				@click="isOpen = true
				$nextTick(() => $refs.modalCloseButton.focus())
				"
			>
				Open Modal
			</button>

			<div
				style="background-color: rgba(0, 0, 0, .5)"
				class="absolute top-0 left-0 flex items-center w-full h-full mx-auto overflow-y-auto shadow-lg"
				x-show="isOpen"
			>
				<div class="container p-4 mx-auto mt-2 overflow-y-auto rounded">
					<div class="px-8 py-8 bg-white rounded">
						<h1 class="mb-3 text-2xl font-bold">Modal Title</h1>
						<div class="modal-body">
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro amet repellat recusandae.</p>
						</div>
						<div class="mt-4">
							<button
								class="px-4 py-3 mt-4 text-sm text-white bg-blue-700 rounded"
								@click="isOpen = false"
								x-ref="modalCloseButton"
							>
								Close Modal
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
