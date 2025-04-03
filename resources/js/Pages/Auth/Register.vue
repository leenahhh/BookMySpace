<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue'
import { gsap } from 'gsap'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
        form.reset('password', 'password_confirmation')
        }
    });
};

const formRef = ref(null)

onMounted(() => {
  gsap.fromTo(
    formRef.value,
    { x: 70, opacity: 0, filter: 'blur(10px)' },
    {
      x: 0,
      opacity: 1,
      filter: 'blur(0px)',
      duration: 0.8,
      ease: 'power3.out',
      delay: 0.3
    }
  )
})

const animateAndNavigate = () => {
  gsap.to(formRef.value, {
    x: 70,
    opacity: 0,
    filter: 'blur(10px)',
    duration: 0.4,
    ease: 'power2.in',
  })
}


</script>

<template>
    <GuestLayout>
      <Head title="Register" />
  
      <form ref="formRef" @submit.prevent="submit" class="w-full max-w-md mx-auto px-2 py-10">
        <h1 class="font-bold text-3xl md:text-4xl text-gray-800 mb-8 font-gSans">
          Register
        </h1>
  
        <!-- Name -->
        <div class="mb-4">
          <InputLabel for="name" value="Name" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>
  
        <!-- Email -->
        <div class="mb-4">
          <InputLabel for="email" value="Email" />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>
  
        <!-- Role -->
        <div class="mb-4">
          <InputLabel for="role" value="Select Role" />
          <select
            id="role"
            v-model="form.role"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
            required
          >
            <option value="" disabled>Select your role</option>
            <option value="customer">Customer</option>
            <option value="entrepreneur">Entrepreneur</option>
            <option value="club">Club/Society</option>
          </select>
          <InputError class="mt-2" :message="form.errors.role" />
        </div>
  
        <!-- Password -->
        <div class="mb-4">
          <InputLabel for="password" value="Password" />
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            required
            autocomplete="new-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>
  
        <!-- Confirm Password -->
        <div class="mb-6">
          <InputLabel for="password_confirmation" value="Confirm Password" />
          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password_confirmation"
            required
            autocomplete="new-password"
          />
          <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>
  
        <!-- Submit -->
        <PrimaryButton
          class="w-full"
          :class="{ 'opacity-50': form.processing }"
          :disabled="form.processing"
        >
          Register
        </PrimaryButton>
  
        <!-- Link to Login -->
        <div class="text-sm mt-6 text-center">
          Already registered?
          <Link
            :href="route('login')"
            @click.prevent="animateAndNavigate()"
            class="ml-1 text-purple-600 hover:underline"
          >
            Log in
          </Link>
        </div>
      </form>
    </GuestLayout>
  </template>
  
