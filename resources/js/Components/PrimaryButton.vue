<script setup>
defineProps({
  processing: {
    type: Boolean,
    default: false,
  },
});
</script>

<template>
  <button
    class="inline-flex items-center justify-center px-6 py-2.5 text-white font-semibold rounded-md
           bg-gradient-to-bl from-[#4568DC] to-[#B06AB3]
           transition-all duration-300 ease-in-out
           active:scale-95
           hover:-translate-y-0.5
           hover:shadow-[rgba(0,0,0,0.4)_0px_2px_4px,rgba(0,0,0,0.3)_0px_7px_13px_-3px,rgba(0,0,0,0.2)_0px_-3px_0px_inset]
           disabled:opacity-75 disabled:cursor-not-allowed"
    :disabled="processing"
  >
    <template v-if="processing">
      <div class="loader"></div>
      <slot />
    </template>
    <template v-else >
      <slot />
    </template>
  </button>
</template>


<style scoped>

.loader {
  width: 20px;
  aspect-ratio: 1;
  padding: 4px;
  border-radius: 50%;
  background: white;
  margin-right: 0.5em;

  --_m: 
    conic-gradient(#0000 10%, #000),
    linear-gradient(#000 0 0) content-box;

  -webkit-mask: var(--_m);
  mask: var(--_m);
  -webkit-mask-composite: source-out;
  mask-composite: subtract;

  animation: l3 1s linear infinite;
}

@keyframes l3 {
  to {
    transform: rotate(1turn);
  }
}

</style>