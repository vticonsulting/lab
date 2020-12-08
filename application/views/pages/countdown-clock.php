<main class="flex-1 p-8">
	<div
		id="countdown-clock"
		x-data="{
			clock: { days: '00', hours: '00', minutes: '00', seconds: '00', remaining: 1000 },
			deadline: '25 December 2020',
			calculateTimeLeft: function () {
				const deadline = this.deadline;
				const remaining = Date.parse(deadline) - Date.parse(new Date());
				const seconds = Math.floor( (remaining/1000) % 60 );
				const minutes = Math.floor( (remaining/1000/60) % 60 );
				const hours = Math.floor( (remaining/(1000*60*60)) % 24 );
				const days = Math.floor( remaining/(1000*60*60*24) );

				return { days, hours, minutes, seconds, remaining };
			},
			startCountdown: function () {
				const countdownIntervalFunction = setInterval(() => {
					const timeLeft = this.calculateTimeLeft();
					this.clock = timeLeft;
					if (timeLeft.remaining <= 0) {
						clearInterval(countdownIntervalFunction);
					}

				}, 1000);
			}
		}"
		x-init="startCountdown()"
		class="flex items-center space-x-2 sm:space-x-4"
	>
		<!-- Days -->
		<div class="flex flex-col items-center justify-between space-y-1">
			<span class="flex items-center justify-center w-16 h-16 text-2xl font-bold border border-gray-200 rounded-sm text-primary sm:text-3xl md:text-4xl sm:w-24 sm:h-24" x-text="clock.days"></span>
			<span class="text-xs text-gray-600 sm:text-sm">Days</span>
		</div>

		<!-- Hours -->
		<div class="flex flex-col items-center justify-between space-y-1">
			<span class="flex items-center justify-center w-16 h-16 text-2xl font-bold border border-gray-200 rounded-sm text-primary sm:text-3xl md:text-4xl sm:w-24 sm:h-24" x-text="clock.hours"></span>
			<span class="text-xs text-gray-600 sm:text-sm">Hours</span>
		</div>

		<!-- Minutes -->
		<div class="flex flex-col items-center justify-between space-y-1">
			<span class="flex items-center justify-center w-16 h-16 text-2xl font-bold border border-gray-200 rounded-sm text-primary sm:text-3xl md:text-4xl sm:w-24 sm:h-24" x-text="clock.minutes"></span>
			<span class="text-xs text-gray-600 sm:text-sm">Minutes</span>
		</div>

		<!-- Seconds -->
		<div class="flex flex-col items-center justify-between space-y-1">
			<span class="flex items-center justify-center w-16 h-16 text-2xl font-bold border border-gray-200 rounded-sm text-primary sm:text-3xl md:text-4xl sm:w-24 sm:h-24" x-text="clock.seconds"></span>
			<span class="text-xs text-gray-600 sm:text-sm">Seconds</span>
		</div>
	</div>
</main>