<div class="" style="">
  <div class="flex justify-center p-8 bg-gray-100" style="min-height: 600px;">
    <div class="w-full max-w-xs mx-auto">

  <!--
  Custom select controls like this require a considerable amount of JS to implement from scratch. We're planning
  to build some low-level libraries to make this easier with popular frameworks like React, Vue, and even Alpine.js
  in the near future, but in the mean time we recommend these reference guides when building your implementation:

  https://www.w3.org/TR/wai-aria-practices/#Listbox
  https://www.w3.org/TR/wai-aria-practices/examples/listbox/listbox-collapsible.html
-->
  <div x-data="Components.customSelect({ open: true, value: 3, selected: 3 })" x-init="init()">
    <label id="listbox-label" class="block text-sm font-medium text-gray-700">
      Assigned to
    </label>
    <div class="relative mt-1">
      <button type="button" x-ref="button" @keydown.arrow-up.stop.prevent="onButtonClick()" @keydown.arrow-down.stop.prevent="onButtonClick()" @click="onButtonClick()" aria-haspopup="listbox" :aria-expanded="open" aria-labelledby="listbox-label" class="relative w-full py-2 pl-3 pr-10 text-left bg-white border border-gray-300 rounded-md shadow-sm cursor-default focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
        <span class="flex items-center">
          <img :src="[&quot;https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80&quot;,&quot;https://images.unsplash.com/photo-1550525811-e5869dd03032?ixlib=rb-1.2.1&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80&quot;,&quot;https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2.25&amp;w=256&amp;h=256&amp;q=80&quot;,&quot;https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80&quot;,&quot;https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80&quot;,&quot;https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80&quot;,&quot;https://images.unsplash.com/photo-1568409938619-12e139227838?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80&quot;,&quot;https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80&quot;,&quot;https://images.unsplash.com/photo-1584486520270-19eca1efcce5?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80&quot;,&quot;https://images.unsplash.com/photo-1561505457-3bcad021f8ee?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80&quot;][value]" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="" class="flex-shrink-0 w-6 h-6 rounded-full">
          <span x-text="[&quot;Wade Cooper&quot;,&quot;Arlene Mccoy&quot;,&quot;Devon Webb&quot;,&quot;Tom Cook&quot;,&quot;Tanya Fox&quot;,&quot;Hellen Schmidt&quot;,&quot;Caroline Schultz&quot;,&quot;Mason Heaney&quot;,&quot;Claudie Smitham&quot;,&quot;Emil Schaefer&quot;][value]" class="block ml-3 truncate">Tom Cook</span>
        </span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 ml-3 pointer-events-none">
          <svg class="w-5 h-5 text-gray-400" x-description="Heroicon name: selector" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
</svg>
        </span>
      </button>

      <div x-show="open" @click.away="open = false" x-description="Select popover, show/hide based on select state." x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute w-full mt-1 bg-white rounded-md shadow-lg" style="display: none;">
        <ul @keydown.enter.stop.prevent="onOptionSelect()" @keydown.space.stop.prevent="onOptionSelect()" @keydown.escape="onEscape()" @keydown.arrow-up.prevent="onArrowUp()" @keydown.arrow-down.prevent="onArrowDown()" x-ref="listbox" tabindex="-1" role="listbox" aria-labelledby="listbox-label" :aria-activedescendant="activeDescendant" class="py-1 overflow-auto text-base rounded-md max-h-56 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" x-max="1">

            <li x-description="Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation." x-state:on="Highlighted" x-state:off="Not Highlighted" id="listbox-item-0" role="option" @click="choose(0)" @mouseenter="selected = 0" @mouseleave="selected = null" :class="{ 'text-white bg-primary-600': selected === 0, 'text-gray-900': !(selected === 0) }" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9">
              <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="" class="flex-shrink-0 w-6 h-6 rounded-full">
                <span x-state:on="Selected" x-state:off="Not Selected" :class="{ 'font-semibold': value === 0, 'font-normal': !(value === 0) }" class="block ml-3 font-normal truncate">
                  Wade Cooper
                </span>
              </div>

              <span x-description="Checkmark, only display for selected option." x-state:on="Highlighted" x-state:off="Not Highlighted" x-show="value === 0" :class="{ 'text-white': selected === 0, 'text-primary-600': !(selected === 0) }" class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600" style="display: none;">
                <svg class="w-5 h-5" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
</svg>
              </span>
            </li>

            <li x-description="Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation." x-state:on="Highlighted" x-state:off="Not Highlighted" id="listbox-item-1" role="option" @click="choose(1)" @mouseenter="selected = 1" @mouseleave="selected = null" :class="{ 'text-white bg-primary-600': selected === 1, 'text-gray-900': !(selected === 1) }" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9">
              <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1550525811-e5869dd03032?ixlib=rb-1.2.1&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="" class="flex-shrink-0 w-6 h-6 rounded-full">
                <span x-state:on="Selected" x-state:off="Not Selected" :class="{ 'font-semibold': value === 1, 'font-normal': !(value === 1) }" class="block ml-3 font-normal truncate">
                  Arlene Mccoy
                </span>
              </div>

              <span x-description="Checkmark, only display for selected option." x-state:on="Highlighted" x-state:off="Not Highlighted" x-show="value === 1" :class="{ 'text-white': selected === 1, 'text-primary-600': !(selected === 1) }" class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600" style="display: none;">
                <svg class="w-5 h-5" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
</svg>
              </span>
            </li>

            <li x-description="Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation." x-state:on="Highlighted" x-state:off="Not Highlighted" id="listbox-item-2" role="option" @click="choose(2)" @mouseenter="selected = 2" @mouseleave="selected = null" :class="{ 'text-white bg-primary-600': selected === 2, 'text-gray-900': !(selected === 2) }" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9">
              <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2.25&amp;w=256&amp;h=256&amp;q=80" alt="" class="flex-shrink-0 w-6 h-6 rounded-full">
                <span x-state:on="Selected" x-state:off="Not Selected" :class="{ 'font-semibold': value === 2, 'font-normal': !(value === 2) }" class="block ml-3 font-normal truncate">
                  Devon Webb
                </span>
              </div>

              <span x-description="Checkmark, only display for selected option." x-state:on="Highlighted" x-state:off="Not Highlighted" x-show="value === 2" :class="{ 'text-white': selected === 2, 'text-primary-600': !(selected === 2) }" class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600" style="display: none;">
                <svg class="w-5 h-5" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
</svg>
              </span>
            </li>

            <li x-description="Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation." x-state:on="Highlighted" x-state:off="Not Highlighted" id="listbox-item-3" role="option" @click="choose(3)" @mouseenter="selected = 3" @mouseleave="selected = null" :class="{ 'text-white bg-primary-600': selected === 3, 'text-gray-900': !(selected === 3) }" class="relative py-2 pl-3 text-white bg-primary-600 cursor-default select-none pr-9">
              <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="" class="flex-shrink-0 w-6 h-6 rounded-full">
                <span x-state:on="Selected" x-state:off="Not Selected" :class="{ 'font-semibold': value === 3, 'font-normal': !(value === 3) }" class="block ml-3 font-semibold truncate">
                  Tom Cook
                </span>
              </div>

              <span x-description="Checkmark, only display for selected option." x-state:on="Highlighted" x-state:off="Not Highlighted" x-show="value === 3" :class="{ 'text-white': selected === 3, 'text-primary-600': !(selected === 3) }" class="absolute inset-y-0 right-0 flex items-center pr-4 text-white">
                <svg class="w-5 h-5" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
</svg>
              </span>
            </li>

            <li x-description="Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation." x-state:on="Highlighted" x-state:off="Not Highlighted" id="listbox-item-4" role="option" @click="choose(4)" @mouseenter="selected = 4" @mouseleave="selected = null" :class="{ 'text-white bg-primary-600': selected === 4, 'text-gray-900': !(selected === 4) }" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9">
              <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="" class="flex-shrink-0 w-6 h-6 rounded-full">
                <span x-state:on="Selected" x-state:off="Not Selected" :class="{ 'font-semibold': value === 4, 'font-normal': !(value === 4) }" class="block ml-3 font-normal truncate">
                  Tanya Fox
                </span>
              </div>

              <span x-description="Checkmark, only display for selected option." x-state:on="Highlighted" x-state:off="Not Highlighted" x-show="value === 4" :class="{ 'text-white': selected === 4, 'text-primary-600': !(selected === 4) }" class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600" style="display: none;">
                <svg class="w-5 h-5" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
</svg>
              </span>
            </li>

            <li x-description="Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation." x-state:on="Highlighted" x-state:off="Not Highlighted" id="listbox-item-5" role="option" @click="choose(5)" @mouseenter="selected = 5" @mouseleave="selected = null" :class="{ 'text-white bg-primary-600': selected === 5, 'text-gray-900': !(selected === 5) }" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9">
              <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="" class="flex-shrink-0 w-6 h-6 rounded-full">
                <span x-state:on="Selected" x-state:off="Not Selected" :class="{ 'font-semibold': value === 5, 'font-normal': !(value === 5) }" class="block ml-3 font-normal truncate">
                  Hellen Schmidt
                </span>
              </div>

              <span x-description="Checkmark, only display for selected option." x-state:on="Highlighted" x-state:off="Not Highlighted" x-show="value === 5" :class="{ 'text-white': selected === 5, 'text-primary-600': !(selected === 5) }" class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600" style="display: none;">
                <svg class="w-5 h-5" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
</svg>
              </span>
            </li>

            <li x-description="Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation." x-state:on="Highlighted" x-state:off="Not Highlighted" id="listbox-item-6" role="option" @click="choose(6)" @mouseenter="selected = 6" @mouseleave="selected = null" :class="{ 'text-white bg-primary-600': selected === 6, 'text-gray-900': !(selected === 6) }" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9">
              <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1568409938619-12e139227838?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="" class="flex-shrink-0 w-6 h-6 rounded-full">
                <span x-state:on="Selected" x-state:off="Not Selected" :class="{ 'font-semibold': value === 6, 'font-normal': !(value === 6) }" class="block ml-3 font-normal truncate">
                  Caroline Schultz
                </span>
              </div>

              <span x-description="Checkmark, only display for selected option." x-state:on="Highlighted" x-state:off="Not Highlighted" x-show="value === 6" :class="{ 'text-white': selected === 6, 'text-primary-600': !(selected === 6) }" class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600" style="display: none;">
                <svg class="w-5 h-5" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
</svg>
              </span>
            </li>

            <li x-description="Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation." x-state:on="Highlighted" x-state:off="Not Highlighted" id="listbox-item-7" role="option" @click="choose(7)" @mouseenter="selected = 7" @mouseleave="selected = null" :class="{ 'text-white bg-primary-600': selected === 7, 'text-gray-900': !(selected === 7) }" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9">
              <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="" class="flex-shrink-0 w-6 h-6 rounded-full">
                <span x-state:on="Selected" x-state:off="Not Selected" :class="{ 'font-semibold': value === 7, 'font-normal': !(value === 7) }" class="block ml-3 font-normal truncate">
                  Mason Heaney
                </span>
              </div>

              <span x-description="Checkmark, only display for selected option." x-state:on="Highlighted" x-state:off="Not Highlighted" x-show="value === 7" :class="{ 'text-white': selected === 7, 'text-primary-600': !(selected === 7) }" class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600" style="display: none;">
                <svg class="w-5 h-5" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
</svg>
              </span>
            </li>

            <li x-description="Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation." x-state:on="Highlighted" x-state:off="Not Highlighted" id="listbox-item-8" role="option" @click="choose(8)" @mouseenter="selected = 8" @mouseleave="selected = null" :class="{ 'text-white bg-primary-600': selected === 8, 'text-gray-900': !(selected === 8) }" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9">
              <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1584486520270-19eca1efcce5?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="" class="flex-shrink-0 w-6 h-6 rounded-full">
                <span x-state:on="Selected" x-state:off="Not Selected" :class="{ 'font-semibold': value === 8, 'font-normal': !(value === 8) }" class="block ml-3 font-normal truncate">
                  Claudie Smitham
                </span>
              </div>

              <span x-description="Checkmark, only display for selected option." x-state:on="Highlighted" x-state:off="Not Highlighted" x-show="value === 8" :class="{ 'text-white': selected === 8, 'text-primary-600': !(selected === 8) }" class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600" style="display: none;">
                <svg class="w-5 h-5" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
</svg>
              </span>
            </li>

            <li x-description="Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation." x-state:on="Highlighted" x-state:off="Not Highlighted" id="listbox-item-9" role="option" @click="choose(9)" @mouseenter="selected = 9" @mouseleave="selected = null" :class="{ 'text-white bg-primary-600': selected === 9, 'text-gray-900': !(selected === 9) }" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9">
              <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1561505457-3bcad021f8ee?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="" class="flex-shrink-0 w-6 h-6 rounded-full">
                <span x-state:on="Selected" x-state:off="Not Selected" :class="{ 'font-semibold': value === 9, 'font-normal': !(value === 9) }" class="block ml-3 font-normal truncate">
                  Emil Schaefer
                </span>
              </div>

              <span x-description="Checkmark, only display for selected option." x-state:on="Highlighted" x-state:off="Not Highlighted" x-show="value === 9" :class="{ 'text-white': selected === 9, 'text-primary-600': !(selected === 9) }" class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600" style="display: none;">
                <svg class="w-5 h-5" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
</svg>
              </span>
            </li>


          <!-- More options... -->
        </ul>
      </div>
    </div>
  </div>

    </div>
  </div>
</div>
