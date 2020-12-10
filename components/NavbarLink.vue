<template>
  <NuxtLink to="/" class="flex items-center space-x-2">
    <Slot />
  </NuxtLink>
</template>

<script>
// class="block px-3 py-2 text-base font-medium text-white transition duration-150 ease-in-out bg-gray-900 rounded-md focus:outline-none focus:text-white focus:bg-gray-700"
export default {
  props: {
    link: {
      type: Object,
      default: () => ({}),
    },
  },
}
</script>
