<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative h-screen w-screen flex">
        <!-- Background Image -->
        <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('/images/1.png');"
        ></div>

        <!-- Logo (Top Left) -->
        <div class="absolute top-6 left-6 z-20">
            <Link href="/">
                <img src="/images/Bms.png" alt="Website Logo" class="w-[460px] h-auto" />
            </Link>
        </div>

        <div class="w-1/3 bg-white p-10 shadow-lg rounded-3xl m-3 flex flex-col justify-center relative z-10 ml-auto">
            <slot />
        </div>
    </div>
</template>
