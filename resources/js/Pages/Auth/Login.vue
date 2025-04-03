<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue'
import { gsap } from 'gsap'

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const formRef = ref(null)

onMounted(() => {
  gsap.fromTo(
    formRef.value,
    { x: -70, opacity: 0, filter: 'blur(10px)' },
    {
      x: 0,
      opacity: 1,
      filter: 'blur(0px)',
      duration: 0.8,
      ease: 'power3.out',
      delay: 0.3
    }
  );
});

const animateAndNavigate = () => {
  gsap.to(formRef.value, {
    x: -70,
    opacity: 0,
    filter: 'blur(10px)',
    duration: 0.4,
    ease: 'power2.in',
  })
}
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <form ref="formRef" @submit.prevent="submit" class="w-full max-w-md mx-auto px-2 py-10">
            <h1 class="text-3xl md:text-[3.2rem] text-gray-800 mb-3 font-gSans">
                Hello,
            </h1>
            <h1 class="font-bold text-3xl md:text-[3.2rem] text-gray-800 mb-8 font-gSans">
                welcome!
            </h1>

            <div class="mb-4">
                <InputLabel for="email" value="Email" class="text-gray-700" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-1 text-sm text-red-500" :message="form.errors.email" />
            </div>

            <div class="mb-4">
                <InputLabel for="password" value="Password" class="text-gray-700" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-1 text-sm text-red-500" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm text-gray-600">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2">Remember me</span>
                </label>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-purple-600 hover:underline"
                    @click.prevent="animateAndNavigate()"
                >
                    Forgot your password?
                </Link>
            </div>

            <PrimaryButton
                class="w-full"
                :class="{ 'opacity-50': form.processing }"
                :disabled="form.processing"
            >
                Log in
            </PrimaryButton>

            <div class="text-sm mt-6 text-center">
                Don’t have an account?
                <Link
                    :href="route('register')"
                    @click.prevent="animateAndNavigate(route('register'))"
                    class="ml-1 text-purple-600 hover:underline"
                >
                    Sign Up
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>


