<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Plus } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3'

const deletePost = (postId) => {
  if (confirm('Are you sure you want to delete this post?')) {
    router.delete(route('content.delete', postId), {
      onSuccess: () => {
        selectedPost.value = null // Close modal if open
      },
    })
  }
}

const selectedPost = ref(null)

const openPostModal = (post) => {
  selectedPost.value = post
}

const closePostModal = () => {
  selectedPost.value = null
}

const isImage = (url) => {
  return /\.(jpeg|jpg|gif|png|webp|svg)$/i.test(url)
}

const props = defineProps({
  processedProfile: {
    type: Object,
    required: true,
  },
  content: {
    type: Array,
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


  // if (form.processing) return;

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
            class="w-44 h-44 rounded-full border object-cover shadow"
          />

          <!-- Info on the Right -->
          <div class="flex-1 font-gSans">
            <!-- Business Name -->
            <h1 class="text-3xl font-semibold text-gray-900">
              {{ processedProfile.name }}
            </h1>

            <!-- Product Type -->
            <div>
              <p class="text-xl text-gray-800 -mt-1 mb-1">{{ processedProfile.product_type }}</p>
            </div>

            <!-- Product Description -->
            <div>
              <p class="text-gray-500">{{ processedProfile.desc }}</p>
            </div>

            <!-- Socials -->
            <a
              :href="processedProfile.socials"
              class="text-purple-500 hover:underline inline-flex items-center gap-1"
              target="_blank"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link-icon lucide-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
              {{ processedProfile.socials }}
            </a>

            <!-- Buttons -->
            <div class="flex items-center gap-3 mt-2">
            <Link
                href="/profile/edit"
                class="inline-flex items-center justify-center px-6 py-2.5 font-semibold rounded-md border border-gray-800 text-slate-800 flex-1 active:scale-95
                        hover:-translate-y-0.5 transition-all duration-300 ease-in-out
                        hover:shadow-[rgba(0,0,0,0.4)_0px_2px_4px,rgba(0,0,0,0.3)_0px_7px_13px_-3px,rgba(0,0,0,0.2)_0px_-3px_0px_inset]
                        disabled:opacity-75 disabled:cursor-not-allowed"
            >
                Edit Profile
            </Link>
            <!-- Button to open the modal -->
            <PrimaryButton 
              @click="openModal"
              :icon="true"
              iconPlacement="left"
              class="flex-1"
            >
              <template #icon>
                <Plus />
              </template>
              Add Post
            </PrimaryButton>

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
                  <div class="mb-4">
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


    <h3 class="text-lg font-semibold mb-4 text-gray-800">Posts</h3>

    <!-- Posts Grid -->
    <div v-if="content.length" class="grid grid-cols-3 gap-2">
      <div
        v-for="post in content"
        :key="post.id"
        class="relative group aspect-square overflow-hidden bg-gray-100 cursor-pointer"
        @click="openPostModal(post)"
      >
        <img
          v-if="isImage(post.content_url)"
          :src="post.content_url"
          alt="Post Image"
          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        />
        <div
          v-else
          class="w-full h-full flex items-center justify-center text-blue-600 text-sm px-4 text-center"
        >
          <a :href="post.content_url" target="_blank" class="underline break-words">
            {{ post.content_url }}
          </a>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-10 text-gray-500 italic">
      No posts yet. Start by adding something!
    </div>

    <!-- Modal -->
    <div v-if="selectedPost" class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center">
      <div class="bg-white w-full max-w-md mx-auto rounded-lg overflow-hidden shadow-lg relative">
        <!-- Close Button -->
        <button @click="selectedPost = null" class="absolute top-3 right-3 text-gray-500 hover:text-black text-lg">
          &times;
        </button>

        <!-- Header -->
         
        <!-- Header -->
        <div class="p-4 border-b font-semibold text-gray-800 flex items-center space-x-3">
          <!-- Business Logo -->
          <img
            :src="processedProfile.logo"
            alt="Business Logo"
            class="w-10 h-10 rounded-full object-cover border"
          />

          <!-- Business Name -->
          <span>{{ processedProfile.name }}</span>
        </div>

        <!-- Image -->
        <img :src="selectedPost.content_url" alt="Post" class="w-full object-cover max-h-[400px]" />

        <!-- Caption & Date -->
        <div class="p-4 flex justify-between items-start">
          <div>
            <p class="text-gray-700 mb-2">{{ selectedPost.content_desc }}</p>
            <p class="text-xs text-gray-500">Posted: {{ new Date(selectedPost.created_at).toLocaleString() }}</p>
          </div>

          <!-- Delete Icon as Image -->
          <button
            @click="deletePost(selectedPost.id)"
            class="ml-4"
            title="Delete Post"
          >
            <img
              src="/images/delete.png"
              alt="Delete"
              class="w-7 h-7 hover:opacity-70"
            />
          </button>
        </div>
      </div>
    </div>


      </div>
    </div>
    </div>
  </AuthenticatedLayout>
</template>
