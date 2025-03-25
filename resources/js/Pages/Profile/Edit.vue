<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';


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