<template>
  <button :class="[$style.button, $style['button-primary']]">
    <slot />
  </button>
</template>

<style module>
.button {
}
.button-primary {
}
</style>
