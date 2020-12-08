<main class="flex-1 text-gray-800 bg-gray-400">
	<div
		class="container px-4 mx-auto"
		x-data="{ quote:'' }"
		x-init="
			fetch('https://api.kanye.rest')
				.then(response => response.json())
				.then(data => quote = data.quote)
		"
	>
		<div class="flex items-center justify-center h-screen text-2xl italic text-center" x-text="`&quot;${quote}&quot;`"></div>
	</div>
</main>
