<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import { gsap } from 'gsap'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import { ImagePlus } from 'lucide-vue-next'

const logoUrl = ref('')
const formRef = ref(null)

const form = useForm({
  name: '',
  product_type: '',
  desc: '',
  socials: '',
  logo: null,
})

const handleImageUpload = (event) => {
  form.logo = event.target.files[0]
  if (form.logo) {
    logoUrl.value = URL.createObjectURL(form.logo)
  }
}

const submitForm = () => {
  // Clear previous logo error
  form.clearErrors('logo')

  // Manual validation for logo upload
  if (!form.logo) {
    form.setError('logo', 'Please upload a logo.')
    return
  }

  if (form.processing) return

  form.post('/save-business-profile', {
    forceFormData: true,
    onSuccess: () => {
      form.reset()
      logoUrl.value = ''
      logoError.value = null
    },
    onFinish: () => {
      form.clearErrors() // Optional: clears all errors on finish
    },
  })
}

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
  )
})
</script>

<template>
    <GuestLayout>
      <Head title="Business Registration" />
  
      <div ref="formRef" class="min-h-screen flex flex-col items-center justify-center">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800 font-gSans">
          Register Your Business
        </h1>
  
        <form @submit.prevent="submitForm" class="w-full max-w-xl flex flex-col gap-4">
            <!-- Logo Upload Section -->
            <div class="flex flex-col justify-center items-center gap-4 mb-2 relative">
                <!-- Upload Preview Container -->
                <div
                    class="relative group w-24 h-24 bg-gray-100/25 rounded-full overflow-hidden shadow-md flex items-center justify-center"
                >
                    <!-- If logo uploaded, show the image -->
                    <img
                        v-if="logoUrl"
                        :src="logoUrl"
                        alt="Logo"
                        class="w-full h-full object-cover transition duration-300"
                    />

                    <!-- If no logo uploaded, show the ImagePlus icon -->
                    <div
                        v-else
                        class="text-gray-500 transition duration-300 pointer-events-none"
                    >
                        <ImagePlus class="h-12 w-12" />
                    </div>

                    <!-- Hover Overlay (different logic depending on whether logo is present) -->
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
                        <!-- Optional: show icon on hover only when image is present -->
                        <template v-if="logoUrl">
                            <ImagePlus class="h-12 w-12 text-gray-200" />
                        </template>
                    </label>
                </div>

                <!-- Hidden File Input -->
                <input
                    type="file"
                    id="profile_pic"
                    @change="handleImageUpload"
                    accept=".jpg, .jpeg, .png"
                    class="hidden"
                    
                />
                <InputError class="mt-1 text-sm" :message="form.errors.logo" />

            </div>

  
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
              rows="5"
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
  
          <!-- Submit Button -->
          <div class="mt-4">
            <PrimaryButton :processing="form.processing" class="w-full">
              <template v-if="form.processing">
                Saving...
              </template>
              <template v-else>
                Save Profile
              </template>
            </PrimaryButton>
          </div>
        </form>
      </div>
    </GuestLayout>
</template>
  

