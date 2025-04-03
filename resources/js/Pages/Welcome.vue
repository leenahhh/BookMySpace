<template>
  <Head title="Welcome" />
  <div
    ref="pageTransition"
    class="hero-section flex flex-col items-start justify-center h-screen text-white px-20 relative overflow-hidden transition-opacity duration-500"
  >
    <div class="blob"></div>

    <div ref="animText" class="text-left z-10 relative">
      <p class="text-8xl font-extrabold pointer-events-none -ml-2 -mb-2">Book</p>
      <p class="text-8xl font-extrabold pointer-events-none -ml-2 -mb-2">My</p>
      <p class="text-8xl font-extrabold pointer-events-none -ml-1">Space</p>
      <p class="text-4xl mt-8 mb-8 font-semibold pointer-events-none">WELCOME</p>

      <WelcomeButton
        :icon="true"
        iconPlacement="right"
        @click="goToLogin"
      >
        <template #icon>
          <ArrowRight />
        </template>
        GET STARTED
      </WelcomeButton>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import gsap from 'gsap';
import WelcomeButton from '@/Components/WelcomeButton.vue';
import { ArrowRight } from 'lucide-vue-next';

const animText = ref(null);
const pageTransition = ref(null);

onMounted(() => {
  const blob = document.querySelector('.blob');

  // Follow cursor movement
  const moveBlob = (e) => {
    gsap.to(blob, {
      x: e.clientX,
      y: e.clientY,
      duration: 2,
      ease: 'power3.out',
    });
  };

  // Morph blob shape randomly
  const generateBlobRadius = () => {
    const r = () => `${Math.floor(Math.random() * 40) + 30}%`;
    return `${r()} ${r()} ${r()} ${r()} / ${r()} ${r()} ${r()} ${r()}`;
  };

  const morphBlob = () => {
    gsap.to(blob, {
      borderRadius: generateBlobRadius(),
      duration: 6,
      ease: 'sine.inOut',
      onComplete: morphBlob,
    });
  };

  // Entry animation for text
  gsap.fromTo(
    animText.value,
    { x: -100, opacity: 0, filter: 'blur(10px)' },
    { x: 0, opacity: 1, filter: 'blur(0px)', duration: 1.5, ease: 'power3.out' }
  );

  // Entry animation for background
  gsap.fromTo(
    pageTransition.value,
    { opacity: 0 },
    { opacity: 1, duration: 0.3, ease: 'power2.out' }
  );

  window.addEventListener('mousemove', moveBlob);
  morphBlob();

  onUnmounted(() => {
    window.removeEventListener('mousemove', moveBlob);
  });
});

function goToLogin() {
  const timeline = gsap.timeline({
    onComplete: () => router.visit(route('login')),
  });

  // Slide text out and blur
  timeline.to(
    animText.value,
    {
      x: 100,
      opacity: 0,
      filter: 'blur(10px)',
      duration: 0.6,
      ease: 'power2.in',
    },
    0
  );

  // Fade out background
  timeline.to(
    pageTransition.value,
    {
      opacity: 0,
      duration: 0.4,
      ease: 'power2.in',
    },
    0.2
  );
}
</script>

<style>
.hero-section {
  position: relative;
  overflow: hidden;
  height: 100vh;
  background-color: #120024;
  z-index: 0;
}

.hero-section::after {
  content: '';
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 0;
  background-image: url('https://grainy-gradients.vercel.app/noise.svg');
  background-repeat: repeat;
  background-size: 200px 200px;
  opacity: 0.05;
  mix-blend-mode: soft-light;
  animation: noiseShift 12s linear infinite;
}

@keyframes noiseShift {
  0% {
    background-position: 0 0;
  }
  100% {
    background-position: 100px 100px;
  }
}

.blob {
  position: fixed;
  top: 0;
  left: 0;
  width: 900px;
  height: 900px;
  pointer-events: none;
  transform: translate(-50%, -50%);
  z-index: 1;
  background: radial-gradient(
    circle,
    rgba(236, 72, 153, 0.25) 0%,
    rgba(168, 85, 247, 0.2) 35%,
    rgba(107, 33, 168, 0.15) 60%,
    rgba(0, 0, 0, 0) 100%
  );
  filter: blur(100px);
  opacity: 1;
  mix-blend-mode: lighten;
  border-radius: 50% 45% 55% 50% / 50% 55% 45% 50%;
  transition: border-radius 4s ease-in-out;
  animation: moveNoise 12s infinite linear;
}
</style>
