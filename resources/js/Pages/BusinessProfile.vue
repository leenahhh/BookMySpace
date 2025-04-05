<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
  processedProfile: {
    type: Object,
    required: true,
  },
});

console.log(JSON.stringify(props.processedProfile,null, 2));
</script>

<template>
  <Head title="User Profile" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">My Profile</h2>
        <Link
          href="/profile/edit"
          class="text-sm text-blue-600 hover:underline"
        >
          Edit Profile
        </Link>
      </div>
    </template>

    <div class="py-12 bg-gray-50 min-h-screen">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Profile Card -->
        <div class="bg-white shadow-md rounded-lg p-6 flex flex-col sm:flex-row sm:items-start gap-6">
          <!-- Logo on the Left -->
          <img
            :src="processedProfile.logo || '/images/profile.avif'"
            alt="Business Logo"
            class="w-32 h-32 rounded-full border object-cover shadow"
          />

          <!-- Info on the Right -->
          <div class="flex-1 space-y-2">
            <!-- Business Name -->
            <h1 class="text-2xl font-extrabold text-gray-900">
              {{ processedProfile.name }}
            </h1>

            <!-- Socials -->
            <div v-if="processedProfile.socials">
              <a
                :href="processedProfile.socials"
                class="ml-2 text-blue-500 hover:underline"
                target="_blank"
              >
                {{ processedProfile.socials }}
              </a>
            </div>

            <!-- Product Type -->
            <div>
              <span class="font-medium text-gray-700">Product Type:</span>
              <span class="ml-2 text-gray-800">{{ processedProfile.product_type }}</span>
            </div>

            <!-- Product Description -->
            <div>
              <span class="font-medium text-gray-700">Description:</span>
              <p class="ml-2 text-gray-700">{{ processedProfile.desc }}</p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 mt-4">
            <Link
                href="/profile/edit"
                class="px-4 py-1 bg-white text-black text-sm rounded border border-black hover:bg-gray-100 transition"
            >
                Edit Profile
            </Link>
            <button
                class="px-4 py-1 bg-white text-black text-sm rounded border border-black hover:bg-gray-100 transition"
            >
                + Add Post
            </button>
            </div>
          </div>
        </div>

        <!-- Posts Section -->
        <div class="bg-white shadow-md rounded-lg p-6">
          <h3 class="text-lg font-semibold mb-2 text-gray-800">Posts</h3>
          <p class="text-sm text-gray-500 italic">
            No posts yet. Start by adding something!
          </p>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>
