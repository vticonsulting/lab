<main class="flex-1 p-16">
	<div class="w-full max-w-3xl mx-auto">

  <fieldset x-data="radioGroup()">
    <legend class="sr-only">
      Privacy setting
    </legend>

    <div class="-space-y-px bg-white rounded-md" x-ref="radiogroup">


          <div :class="{ 'border-gray-200': !(active === 0), 'bg-primary-50 border-primary-200 z-10': active === 0 }" class="relative flex p-4 border border-gray-200 rounded-tl-md rounded-tr-md">
            <div class="flex items-center h-5">
              <input id="settings-option-0" name="privacy_setting" type="radio" @click="select(0)" @keydown.space="select(0)" @keydown.arrow-up="onArrowUp(0)" @keydown.arrow-down="onArrowDown(0)" class="w-4 h-4 text-primary-600 border-gray-300 cursor-pointer focus:ring-primary-500" checked="">
            </div>
            <label for="settings-option-0" class="flex flex-col ml-3 cursor-pointer">
              <span :class="{ 'text-primary-900': active === 0, 'text-gray-900': !(active === 0) }" class="block text-sm font-medium text-gray-900">
                Public access
              </span>
              <span :class="{ 'text-primary-700': active === 0, 'text-gray-500': !(active === 0) }" class="block text-sm text-gray-500">
                This project would be available to anyone who has the link
              </span>
            </label>
          </div>


          <div :class="{ 'border-gray-200': !(active === 1), 'bg-primary-50 border-primary-200 z-10': active === 1 }" class="relative z-10 flex p-4 border border-primary-200 bg-primary-50">
            <div class="flex items-center h-5">
              <input id="settings-option-1" name="privacy_setting" type="radio" @click="select(1)" @keydown.space="select(1)" @keydown.arrow-up="onArrowUp(1)" @keydown.arrow-down="onArrowDown(1)" class="w-4 h-4 text-primary-600 border-gray-300 cursor-pointer focus:ring-primary-500">
            </div>
            <label for="settings-option-1" class="flex flex-col ml-3 cursor-pointer">
              <span :class="{ 'text-primary-900': active === 1, 'text-gray-900': !(active === 1) }" class="block text-sm font-medium text-primary-900">
                Private to Project Members
              </span>
              <span :class="{ 'text-primary-700': active === 1, 'text-gray-500': !(active === 1) }" class="block text-sm text-primary-700">
                Only members of this project would be able to access
              </span>
            </label>
          </div>


          <div :class="{ 'border-gray-200': !(active === 2), 'bg-primary-50 border-primary-200 z-10': active === 2 }" class="relative flex p-4 border border-gray-200 rounded-bl-md rounded-br-md">
            <div class="flex items-center h-5">
              <input id="settings-option-2" name="privacy_setting" type="radio" @click="select(2)" @keydown.space="select(2)" @keydown.arrow-up="onArrowUp(2)" @keydown.arrow-down="onArrowDown(2)" class="w-4 h-4 text-primary-600 border-gray-300 cursor-pointer focus:ring-primary-500">
            </div>
            <label for="settings-option-2" class="flex flex-col ml-3 cursor-pointer">
              <span :class="{ 'text-primary-900': active === 2, 'text-gray-900': !(active === 2) }" class="block text-sm font-medium text-gray-900">
                Private to you
              </span>
              <span :class="{ 'text-primary-700': active === 2, 'text-gray-500': !(active === 2) }" class="block text-sm text-gray-500">
                You are the only one able to access this project
              </span>
            </label>
          </div>

    </div>
  </fieldset>

    </div>
</main>
