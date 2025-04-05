<template>
    
      <!-- Title & Description -->
      <div class="px-6 pt-6">
        <h2 class="text-xl font-bold text-gray-800">Edit Business Profile</h2>
        <p class="text-sm text-gray-500 mt-1">
          Update your business name, product type, logo and socials.
        </p>
      </div>
  
      <!-- Main Form Layout -->
      <form @submit.prevent="submitForm" class="w-full flex flex-col sm:flex-row gap-6 px-6 py-6">
        <!-- Logo Upload Section (Left) -->
        <div class="flex flex-col items-center w-full sm:w-1/3">
          <div
            class="relative group w-48 h-48 bg-gray-100/25 rounded-full overflow-hidden shadow-md flex items-center justify-center"
          >
            <img
              v-if="logoUrl"
              :src="logoUrl"
              alt="Logo"
              class="w-full h-full object-cover transition duration-300"
            />
            <div
              v-else
              class="text-gray-500 transition duration-300 pointer-events-none"
            >
              <ImagePlus class="h-16 w-16" />
            </div>
            <label
              for="profile_pic"
              :class="[
                'absolute inset-0 transition duration-300 cursor-pointer',
                logoUrl
                  ? 'bg-black/40 backdrop-blur-sm flex items-center justify-center text-white opacity-0 group-hover:opacity-100'
                  : 'bg-black/10 group-hover:bg-black/20'
              ]"
              title="Click to upload logo"
            >
              <template v-if="logoUrl">
                <ImagePlus class="h-12 w-12 text-gray-200" />
              </template>
            </label>
          </div>
  
          <input
            type="file"
            id="profile_pic"
            @change="handleImageUpload"
            accept=".jpg, .jpeg, .png"
            class="hidden"
          />
          <InputError class="mt-2 text-sm" :message="form.errors.logo" />
        </div>
  
        <!-- Input Fields Section (Right) -->
        <div class="flex-1 flex flex-col gap-4">
          <!-- Business Name -->
          <div>
            <InputLabel for="name" value="Business Name" />
            <TextInput
              id="name"
              v-model="form.name"
              type="text"
              placeholder="e.g. Creative Studio, Lanka Crafters"
              class="mt-1 block w-full"
              required
            />
            <InputError class="mt-1" :message="form.errors.name" />
          </div>
  
          <!-- Product Type -->
          <div>
            <InputLabel for="product_type" value="Product Type" />
            <TextInput
              id="product_type"
              v-model="form.product_type"
              type="text"
              placeholder="e.g. Handmade Goods, Services, Software"
              class="mt-1 block w-full"
              required
            />
            <InputError class="mt-1" :message="form.errors.product_type" />
          </div>
  
          <!-- Description -->
          <div>
            <InputLabel for="desc" value="Product Description" />
            <textarea
              id="desc"
              v-model="form.desc"
              rows="4"
              placeholder="Tell us about what you offer or create..."
              class="mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-purple-500 focus:ring-purple-500"
              required
            ></textarea>
            <InputError class="mt-1" :message="form.errors.desc" />
          </div>
  
          <!-- Socials -->
          <div>
            <InputLabel for="socials" value="Business Socials" />
            <TextInput
              id="socials"
              v-model="form.socials"
              type="text"
              placeholder="e.g. https://instagram.com/yourhandle"
              class="mt-1 block w-full"
              required
            />
            <InputError class="mt-1" :message="form.errors.socials" />
          </div>
  
          <!-- Submit Button (Right-Aligned) -->
          <div class="flex justify-end">
            <PrimaryButton :processing="form.processing" class="w-fit">
              <template v-if="form.processing">
                Saving...
              </template>
              <template v-else>
                Save Profile
              </template>
            </PrimaryButton>
          </div>
        </div>
      </form>
    
  </template>
  
  <script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ImagePlus } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';


// Accept props
const props = defineProps({
  processedProfile: {
    type: Object, // should be Object, not Array
    required: true,
  },
});

// Setup the form and pre-fill it with props
const form = useForm({
  name: props.processedProfile.name || '',
  product_type: props.processedProfile.product_type || '',
  desc: props.processedProfile.desc || '',
  socials: props.processedProfile.socials || '',
  logo: null, // File upload, default null
});

// Image preview
const logoUrl = ref(props.processedProfile.logo || null);

// Handle image upload
const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.logo = file;
    logoUrl.value = URL.createObjectURL(file);
  }
};

// Submit handler
const submitForm = () => {
  router.post(route('business.profile.update'), form, {
    preserveScroll: true,
    onSuccess: () => {
      alert('Business profile updated successfully!');
    },
  });
};
</script>

  