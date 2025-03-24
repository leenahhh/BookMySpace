<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';

const businessName = ref('');
const productType = ref('');
const productDescription = ref('');
const businessSocials = ref('');
const logoFile = ref(null);
const profilePicUrl = ref(''); // fallback image
const loading = ref(false);



const handleImageUpload = (event) => {
  logoFile.value = event.target.files[0];
  if (logoFile.value) {
    profilePicUrl.value = URL.createObjectURL(logoFile.value);
  }
};

const saveProfile = () => {
  if (loading.value) return;
  loading.value = true;

  const formData = new FormData();
  formData.append('name', businessName.value);
  formData.append('product_type', productType.value);
  formData.append('desc', productDescription.value);
  formData.append('socials', businessSocials.value);
  formData.append('logo', logoFile.value);

  Inertia.post('/save-business-profile', formData, {
    onSuccess: () => {
      // optional: reset form fields
      businessName.value = '';
      productType.value = '';
      productDescription.value = '';
      businessSocials.value = '';
      profilePicUrl.value = '';
      logoFile.value = null;
    },
    onFinish: () => {
      loading.value = false;
    },
    onError: (errors) => {
      console.error(errors);
    },
  });
};



</script>

<template>
    <GuestLayout>
        <Head title="Business Registration" />

        <div class="min-h-screen flex flex-col items-center justify-center">
            
                <h1 class="text-3xl font-bold mb-4 text-center text-gray-800">Register Your Business</h1>

                <div class="flex flex-col items-center w-full mb-2">
                    <!-- Profile Picture Upload -->
                    <div class="flex items-center gap-4">
                        <div class="flex w-16 aspect-square bg-gray-300 rounded-full overflow-hidden">
                            <template v-if="profilePicUrl">
                                <img
                                    :src="profilePicUrl"
                                    alt="Profile Picture"
                                    class="w-16 h-16 object-fit"
                                />
                            </template>
                            <template v-else>
                                
                            </template>
                        </div>
                        <input
                            type="file"
                            id="profile_pic"
                            @change="handleImageUpload"
                            accept=".jpg, .png, .jpeg"
                            class="hidden"
                        />
                        <!-- Custom button styled as a label -->
                        <label
                            for="profile_pic"
                            class="cursor-pointer w-fit bg-black text-white p-2 rounded-xl font-semibold shadow-md hover:shadow-xl transition duration-30"
                        >
                            Upload Logo
                        </label>
                    </div>

                    <!-- Single-column Business Form Fields -->
                    <div class="w-full flex flex-col gap-2">
                        <div>
                            <label class="block mb-1 text-gray-700 font-semibold">Business Name</label>
                            <input
                                v-model="businessName"
                                type="text"
                                placeholder="Enter business name"
                                class="w-full border rounded-xl p-2 focus:outline-none focus:ring-2 focus:ring-purple-400 shadow-sm"
                            />
                        </div>
                        <div>
                            <label class="block mb-1 text-gray-700 font-semibold">Product Type</label>
                            <input
                                v-model="productType"
                                type="text"
                                placeholder="e.g., Handicrafts, Services"
                                class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-purple-400 shadow-sm"
                            />
                        </div>
                        <div>
                            <label class="block mb-1 text-gray-700 font-semibold">Product Description</label>
                            <textarea
                                v-model="productDescription"
                                placeholder="Describe your products or services..."
                                rows="4"
                                class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-purple-400 shadow-sm"
                            ></textarea>
                        </div>
                        <div>
                            <label class="block mb-1 text-gray-700 font-semibold">Business Socials</label>
                            <input
                                v-model="businessSocials"
                                type="text"
                                placeholder="Enter social media links separated by commas"
                                class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-purple-400 shadow-sm"
                            />
                        </div>
                    </div>

                    <div class="w-full flex gap-4 mt-8">
                        <button
                        @click.prevent="saveProfile"
                        :disabled="loading"
                        class="w-full bg-black text-white py-3 rounded-xl font-semibold shadow-md hover:shadow-xl transition duration-300"
                        >
                        <template v-if="loading">
                            <svg class="animate-spin h-5 w-5 inline-block mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                            </svg>
                            Saving...
                        </template>
                        <template v-else>
                            Save Profile
                        </template>
                        </button>
                    </div>
                </div>
            
        </div>
    </GuestLayout>
</template>

