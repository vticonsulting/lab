<div class="" style="">

	<div class="bg-gray-50" x-data="{ openPanel: 0 }">
		<div class="px-4 py-12 mx-auto max-w-7xl sm:py-16 sm:px-6 lg:px-8">
			<div class="max-w-3xl mx-auto divide-y-2 divide-gray-200">
				<h2 class="text-3xl font-extrabold text-center text-gray-900 sm:text-4xl">
					Frequently asked questions
				</h2>
				<dl class="mt-6 space-y-6 divide-y divide-gray-200">

					<div class="pt-6">
						<dt class="text-lg">
							<button x-description="Expand/collapse question button" @click="openPanel = (openPanel === 0 ? null : 0)" class="flex items-start justify-between w-full text-left text-gray-400" x-bind:aria-expanded="openPanel === 0">
								<span class="font-medium text-gray-900">
									What's the best thing about Switzerland?
								</span>
								<span class="flex items-center ml-6 h-7">
									<svg class="w-6 h-6 transform rotate-0" x-description="Heroicon name: chevron-down" x-state:on="Open" x-state:off="Closed" x-bind:class="{ '-rotate-180': openPanel === 0, 'rotate-0': !(openPanel === 0) }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
									</svg>
								</span>
							</button>
						</dt>
						<dd class="pr-12 mt-2" x-show="openPanel === 0" style="display: none;">
							<p class="text-base text-gray-500">
								I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.
							</p>
						</dd>
					</div>

					<div class="pt-6">
						<dt class="text-lg">
							<button x-description="Expand/collapse question button" @click="openPanel = (openPanel === 1 ? null : 1)" class="flex items-start justify-between w-full text-left text-gray-400" x-bind:aria-expanded="openPanel === 1">
								<span class="font-medium text-gray-900">
									How do you make holy water?
								</span>
								<span class="flex items-center ml-6 h-7">
									<svg class="w-6 h-6 transform rotate-0" x-description="Heroicon name: chevron-down" x-state:on="Open" x-state:off="Closed" x-bind:class="{ '-rotate-180': openPanel === 1, 'rotate-0': !(openPanel === 1) }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
									</svg>
								</span>
							</button>
						</dt>
						<dd class="pr-12 mt-2" x-show="openPanel === 1" style="display: none;">
							<p class="text-base text-gray-500">
								You boil the hell out of it. Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam aut tempora vitae odio inventore fuga aliquam nostrum quod porro. Delectus quia facere id sequi expedita natus.
							</p>
						</dd>
					</div>

					<div class="pt-6">
						<dt class="text-lg">
							<button x-description="Expand/collapse question button" @click="openPanel = (openPanel === 2 ? null : 2)" class="flex items-start justify-between w-full text-left text-gray-400" x-bind:aria-expanded="openPanel === 2">
								<span class="font-medium text-gray-900">
									What do you call someone with no body and no nose?
								</span>
								<span class="flex items-center ml-6 h-7">
									<svg class="w-6 h-6 transform rotate-0" x-description="Heroicon name: chevron-down" x-state:on="Open" x-state:off="Closed" x-bind:class="{ '-rotate-180': openPanel === 2, 'rotate-0': !(openPanel === 2) }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
									</svg>
								</span>
							</button>
						</dt>
						<dd class="pr-12 mt-2" x-show="openPanel === 2" style="display: none;">
							<p class="text-base text-gray-500">
								Nobody knows. Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa, voluptas ipsa quia excepturi, quibusdam natus exercitationem sapiente tempore labore voluptatem.
							</p>
						</dd>
					</div>

					<div class="pt-6">
						<dt class="text-lg">
							<button x-description="Expand/collapse question button" @click="openPanel = (openPanel === 3 ? null : 3)" class="flex items-start justify-between w-full text-left text-gray-400" x-bind:aria-expanded="openPanel === 3">
								<span class="font-medium text-gray-900">
									Why do you never see elephants hiding in trees?
								</span>
								<span class="flex items-center ml-6 h-7">
									<svg class="w-6 h-6 transform rotate-0" x-description="Heroicon name: chevron-down" x-state:on="Open" x-state:off="Closed" x-bind:class="{ '-rotate-180': openPanel === 3, 'rotate-0': !(openPanel === 3) }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
									</svg>
								</span>
							</button>
						</dt>
						<dd class="pr-12 mt-2" x-show="openPanel === 3" style="display: none;">
							<p class="text-base text-gray-500">
								Because they're so good at it. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.
							</p>
						</dd>
					</div>

					<div class="pt-6">
						<dt class="text-lg">
							<button x-description="Expand/collapse question button" @click="openPanel = (openPanel === 4 ? null : 4)" class="flex items-start justify-between w-full text-left text-gray-400" x-bind:aria-expanded="openPanel === 4">
								<span class="font-medium text-gray-900">
									Why can't you hear a pterodactyl go to the bathroom?
								</span>
								<span class="flex items-center ml-6 h-7">
									<svg class="w-6 h-6 transform rotate-0" x-description="Heroicon name: chevron-down" x-state:on="Open" x-state:off="Closed" x-bind:class="{ '-rotate-180': openPanel === 4, 'rotate-0': !(openPanel === 4) }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
									</svg>
								</span>
							</button>
						</dt>
						<dd class="pr-12 mt-2" x-show="openPanel === 4" style="display: none;">
							<p class="text-base text-gray-500">
								Because the pee is silent. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam, quas voluptatibus ex culpa ipsum, aspernatur blanditiis fugiat ullam magnam suscipit deserunt illum natus facilis atque vero consequatur! Quisquam, debitis error.
							</p>
						</dd>
					</div>

					<div class="pt-6">
						<dt class="text-lg">
							<button x-description="Expand/collapse question button" @click="openPanel = (openPanel === 5 ? null : 5)" class="flex items-start justify-between w-full text-left text-gray-400" x-bind:aria-expanded="openPanel === 5">
								<span class="font-medium text-gray-900">
									Why did the invisible man turn down the job offer?
								</span>
								<span class="flex items-center ml-6 h-7">
									<svg class="w-6 h-6 transform rotate-0" x-description="Heroicon name: chevron-down" x-state:on="Open" x-state:off="Closed" x-bind:class="{ '-rotate-180': openPanel === 5, 'rotate-0': !(openPanel === 5) }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
									</svg>
								</span>
							</button>
						</dt>
						<dd class="pr-12 mt-2" x-show="openPanel === 5" style="display: none;">
							<p class="text-base text-gray-500">
								He couldn't see himself doing it. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eveniet perspiciatis officiis corrupti tenetur. Temporibus ut voluptatibus, perferendis sed unde rerum deserunt eius.
							</p>
						</dd>
					</div>

				</dl>
			</div>
		</div>
	</div>

</div>
