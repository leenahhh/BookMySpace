<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm  } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  processedProfile: {
    type: Object,
    required: true,
  },
});


const isModalOpen = ref(false);
// const image = ref(null);
// const description = ref('');
const contentUrl = ref(''); // For image preview

// Initialize the form
const form = useForm({
  content_desc: '', // description
  content_image: null, // image file
});

// Handle image file change and show preview
const handleImageUpload = (event) => {
  const file = event.target.files[0];

  if (file) {
    form.content_image = file; // Store the file in the form data
    const reader = new FileReader();
    reader.onload = (e) => {
      contentUrl.value = e.target.result; // Set image preview
    };
    reader.readAsDataURL(file);
  }
};

// Open and close modal
const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  contentUrl.value = ''; // Clear preview
};

// Handle form submission
const submitForm = () => {
  // Clear previous errors for content image
  form.clearErrors('content_image');

  // Manual validation for content image
  if (!form.content_image) {
    form.setError('content_image', 'Please upload a content image.');
    return;
  }

  if (form.processing) return;

  // Post the data to the server using Inertia's router
  form.post('/content', {
    forceFormData: true, // This is important to send files correctly
    onSuccess: () => {
      form.reset(); // Reset form fields
      contentUrl.value = ''; // Clear the image preview
      closeModal(); // Close the modal
    },
    onFinish: () => {
      form.clearErrors(); // Optionally clear errors on finish
    },
  });
};

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
              @click="openModal"
              class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700"
            >
              + Add Post
            </button>

            <!-- Modal -->
            <div v-if="isModalOpen" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
              <div class="bg-white p-6 rounded-lg w-96">
                <!-- Close Button -->
                <button @click="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-black">
                  &times;
                </button>

                <h3 class="text-lg font-bold mb-4 text-center">Upload Post</h3>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="flex flex-col items-center justify-center space-y-4">
  <!-- Image Upload Area with Polaroid Style -->
  <div class="w-48 h-48 bg-white border-2 border-gray-400 rounded-md flex justify-center items-center relative">
    <input 
      type="file" 
      @change="handleImageUpload" 
      accept="image/*" 
      class="absolute opacity-0 w-48 h-48 cursor-pointer"
    />
    <!-- Image Preview -->
    <div v-if="contentUrl" class="w-full h-full bg-gray-200 rounded-md overflow-hidden">
      <img :src="contentUrl" alt="Image Preview" class="w-full h-full object-cover" />
    </div>
    <!-- Placeholder text for image upload -->
    <div v-else class="text-center text-gray-400">
      <p class="text-5xl">+</p> <!-- Adjusted font size for larger text -->
    </div>
  </div>

  <!-- Description Field -->
  <div class="mb-4 w-full max-w-xs">
    <textarea
      v-model="form.content_desc"
      placeholder="Enter description..."
      rows="4"
      class="border p-2 w-full rounded"
    ></textarea>
    <span v-if="form.errors.content_desc" class="text-red-500 text-sm">{{ form.errors.content_desc }}</span>
  </div>

  <!-- Submit Button and Cancel Button -->
  <div class="flex justify-center gap-4 mt-4">
    <!-- Submit Button -->
    <button 
      type="submit"
      class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700"
    >
      Post
    </button>

    <!-- Cancel Button (Close the Modal) -->
    <button
      type="button"
      @click="closeModal"
      class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400"
    >
      Cancel
    </button>
  </div>
</form>

              </div>
            </div>
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
