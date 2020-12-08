<template>
  <svg
    class="w-16 h-16"
    style="margin: auto; background: none; display: block; shape-rendering: auto"
    viewBox="0 0 100 100"
  >
    <defs>
      <path id="path" d="M50 15A15 35 0 0 1 50 85A15 35 0 0 1 50 15" fill="none"></path>
      <path id="patha" d="M0 0A15 35 0 0 1 0 70A15 35 0 0 1 0 0" fill="none"></path>
    </defs>
    <g transform="rotate(0 50 50)">
      <use xlink:href="#path" stroke="#777" stroke-width="3"></use>
    </g>
    <g transform="rotate(60 50 50)">
      <use xlink:href="#path" stroke="#999" stroke-width="3"></use>
    </g>
    <g transform="rotate(120 50 50)">
      <use xlink:href="#path" stroke="#bbb" stroke-width="3"></use>
    </g>
    <g transform="rotate(0 50 50)">
      <circle cx="50" cy="15" r="9" fill="#777">
        <animateMotion dur="1s" repeatCount="indefinite" begin="0s">
          <mpath xlink:href="#patha"></mpath>
        </animateMotion>
      </circle>
    </g>
    <g transform="rotate(60 50 50)">
      <circle cx="50" cy="15" r="9" fill="#999">
        <animateMotion dur="1s" repeatCount="indefinite" begin="-0.16666666666666666s">
          <mpath xlink:href="#patha"></mpath>
        </animateMotion>
      </circle>
    </g>
    <g transform="rotate(120 50 50)">
      <circle cx="50" cy="15" r="9" fill="#bbb">
        <animateMotion dur="1s" repeatCount="indefinite" begin="-0.3333333333333333s">
          <mpath xlink:href="#patha"></mpath>
        </animateMotion>
      </circle>
    </g>
  </svg>
</template>
