<main class="flex-1 p-8">
	<header class="prose">
		<h2>Tagify</h2>
	</header>

	<div
		x-cloak
		wire:ignore
		x-data="{tagify: null}"
		class="w-full mt-8"
		x-init="tagify = new Tagify($refs['tag-input'])"
	>
		<input x-ref="tag-input" placeholder="Add tags...">
	</div>
</main>
