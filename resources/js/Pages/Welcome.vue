<template>
    <div class="flex flex-col items-start justify-center h-screen bg-[#6b05f0] text-white pl-20 relative">
        <!-- Title -->
        <div class="text-left">
            <h1 class="text-8xl font-extrabold leading-none">
                Book<br>My<br>Space
            </h1><br>
            <p class="text-2xl mt-2 font-semibold">WELCOME</p>
        </div>

        <!-- Buttons -->
        <div class="flex space-x-6 mt-10 ">
            <Link 
                :href="route('login')" 
                class="px-6 py-3 border-2 border-white text-white text-lg rounded-full transition hover:bg-white hover:text-purple-700">
                GET STARTED
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
