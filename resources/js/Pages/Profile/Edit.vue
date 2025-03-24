<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
//saving profile 
import { ref } from 'vue';
import axios from 'axios';

const businessName = ref('');
const productType = ref('');
const productDescription = ref('');
const businessSocials = ref('');
const logoFile = ref(null);
const profilePicUrl = ref('/default-profile.png'); // fallback image

const handleImageUpload = (event) => {
  logoFile.value = event.target.files[0];
  if (logoFile.value) {
    profilePicUrl.value = URL.createObjectURL(logoFile.value);
  }
};

const saveProfile = async () => {
  const formData = new FormData();
  formData.append('name', businessName.value);
  formData.append('product_type', productType.value);
  formData.append('desc', productDescription.value);
  formData.append('socials', businessSocials.value);
  formData.append('logo', logoFile.value);

  try {
    const response = await axios.post('/save-business-profile', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    alert(response.data.message);
    // Reset form if needed:
    businessName.value = '';
    productType.value = '';
    productDescription.value = '';
    businessSocials.value = '';
    profilePicUrl.value = '/default-profile.png';
    logoFile.value = null;
  } catch (error) {
    alert('Error: ' + error.response.data.message || 'Something went wrong.');
  }
};

const cancelProfile = () => {
  businessName.value = '';
  productType.value = '';
  productDescription.value = '';
  businessSocials.value = '';
  logoFile.value = null;
  profilePicUrl.value = '/default-profile.png';
};


defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Profile
            </h2>
        </template>

        <div class="w-full p-8 bg-[#f8f9fa] min-h-screen">
            <div class="bg-white w-full rounded-2xl shadow-xl p-8">
              <div class="flex flex-col md:flex-row items-center md:items-start gap-8 w-full">
                <!-- Profile Picture and Image Upload -->
                <div class="flex flex-col items-center gap-4">
                  <img
                    :src="profilePicUrl"
                    alt="Profile Picture"
                    class="w-40 h-40 rounded-full border-4 border-blue-200 object-cover"
                  />
                  <input
                    type="file"
                    @change="handleImageUpload"
                    class="text-sm text-gray-500"
                  />
                </div>

                <!-- Business Form Fields -->
                <div class="w-full">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label class="block mb-1 text-gray-700">Business Name</label>
                      <input
                        v-model="businessName"
                        type="text"
                        placeholder="Enter business name"
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-300"
                      />
                    </div>
                    <div>
                      <label class="block mb-1 text-gray-700">Product Type</label>
                      <input
                        v-model="productType"
                        type="text"
                        placeholder="e.g., Handicrafts, Services"
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-300"
                      />
                    </div>
                    <div class="md:col-span-2">
                      <label class="block mb-1 text-gray-700">Product Description</label>
                      <textarea
                        v-model="productDescription"
                        placeholder="Describe your products or services..."
                        rows="4"
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-300"
                      ></textarea>
                    </div>
                    <div class="md:col-span-2">
                      <label class="block mb-1 text-gray-700">Business Socials</label>
                      <input
                        v-model="businessSocials"
                        type="text"
                        placeholder="Enter social media links separated by commas"
                        class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-300"
                      />
                    </div>
                  </div>

                  <div class="flex gap-4 mt-8">
                    <button
                      @click.prevent="saveProfile"
                      class="bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-600"
                    >
                      Save Profile
                    </button>
                    <button
                      @click="cancelProfile"
                      class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-300"
                    >
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
  data() {
    return {
      businessName: '',
      productType: '',
      productDescription: '',
      businessSocials: '',
      profilePic: null,
      profilePicUrl: '/images/profile.avif', // Default profile image
    };
  },
  methods: {
    handleImageUpload(event) {
      this.profilePic = event.target.files[0];
      if (this.profilePic) {
        this.profilePicUrl = URL.createObjectURL(this.profilePic);
      }
    },
    async saveProfile() {
      const formData = new FormData();
      formData.append('name', this.businessName);
      formData.append('product_type', this.productType);
      formData.append('desc', this.productDescription);
      formData.append('socials', this.businessSocials);
      formData.append('profile_pic', this.profilePic);
      formData.append('status', 'pending');
      formData.append('rejected_reason', ''); // You can leave this empty or add logic later

      try {
        await axios.post('/api/save-profile', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        alert('Profile saved successfully!');
      } catch (error) {
        console.error(error);
        alert('Failed to save profile.');
      }
    },
    cancelProfile() {
      // Reset form
      this.businessName = '';
      this.productType = '';
      this.productDescription = '';
      this.businessSocials = '';
      this.profilePic = null;
      this.profilePicUrl = '/images/profile.avif'; // Default profile image
    },
  },
};
</script>