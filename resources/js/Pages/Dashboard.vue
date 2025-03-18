<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, computed } from 'vue';
</script>

<template>
  <Head title="Home" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Welcome Admin!
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div>
            <!-- Button to open the modal -->
            <button
              @click="openForm"
              class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300"
            >
              Create Stalls
            </button>

            <!-- Modal for stall creation -->
            <div
              v-if="showForm"
              class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
            >
              <!-- Success Notification -->
              <div
                v-if="successMessage"
                class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white py-2 px-4 rounded-lg shadow-md"
              >
                {{ successMessage }}
              </div>
              <div class="bg-white p-6 rounded-lg w-96 text-center shadow-lg">
                <form @submit.prevent="submitForm" class="grid grid-cols-2 gap-4">
                  <!-- Stall Name Field -->
                  <div class="mb-4 col-span-2">
                    <label for="stallName" class="block text-sm font-medium text-gray-700">
                      Stall Name
                    </label>
                    <input
                      v-model="stall.stallName"
                      id="stallName"
                      type="text"
                      required
                      class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    />
                    <p v-if="errors.stallName" class="text-red-500 text-sm">{{ errors.stallName }}</p>
                  </div>

                  <!-- Stall Description Field -->
                  <div class="mb-4 col-span-2">
                    <label for="stallDescription" class="block text-sm font-medium text-gray-700">
                      Stall Description
                    </label>
                    <textarea
                      v-model="stall.stallDescription"
                      id="stallDescription"
                      required
                      class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    ></textarea>
                    <p v-if="errors.stallDescription" class="text-red-500 text-sm">{{ errors.stallDescription }}</p>
                  </div>

                  <!-- Stall Date Field -->
                  <div class="mb-4">
                    <label for="stallDate" class="block text-sm font-medium text-gray-700">
                      Stall Date
                    </label>
                    <input
                      v-model="stall.stallDate"
                      type="date"
                      required
                      class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    />
                    <p v-if="errors.stallDate" class="text-red-500 text-sm">{{ errors.stallDate }}</p>
                  </div>

                  <!-- Stall Location Field -->
                  <div class="mb-4">
                    <label for="stallLocation" class="block text-sm font-medium text-gray-700">
                      Stall Location
                    </label>
                    <input
                      v-model="stall.stallLocation"
                      id="stallLocation"
                      type="text"
                      required
                      class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    />
                    <p v-if="errors.stallLocation" class="text-red-500 text-sm">{{ errors.stallLocation }}</p>
                  </div>

                  <!-- Start Time Field -->
                  <div class="mb-4">
                    <label for="startTime" class="block text-sm font-medium text-gray-700">
                      Start Time
                    </label>
                    <input
                      v-model="stall.startTime"
                      type="time"
                      required
                      class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    />
                    <p v-if="errors.startTime" class="text-red-500 text-sm">{{ errors.startTime }}</p>
                  </div>

                  <!-- End Time Field -->
                  <div class="mb-4">
                    <label for="endTime" class="block text-sm font-medium text-gray-700">
                      End Time
                    </label>
                    <input
                      v-model="stall.endTime"
                      type="time"
                      required
                      class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    />
                    <p v-if="errors.endTime" class="text-red-500 text-sm">{{ errors.endTime }}</p>
                  </div>

                  <!-- Contact Email Field -->
                  <div class="mb-4">
                    <label for="contactEmail" class="block text-sm font-medium text-gray-700">
                      Contact Email
                    </label>
                    <input
                      v-model="stall.contactEmail"
                      type="email"
                      required
                      class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    />
                    <p v-if="errors.contactEmail" class="text-red-500 text-sm">{{ errors.contactEmail }}</p>
                  </div>

                  <!-- Stall Booking Cost Field -->
                  <div class="mb-4">
                    <label for="stallBookingCost" class="block text-sm font-medium text-gray-700">
                      Stall Booking Cost
                    </label>
                    <input
                      v-model="stall.stallBookingCost"
                      type="number"
                      required
                      class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
                    />
                    <p v-if="errors.stallBookingCost" class="text-red-500 text-sm">{{ errors.stallBookingCost }}</p>
                  </div>

                  <!-- Is Available Field -->
                  <div class="mb-4 col-span-2 flex items-center">
                    <input
                      v-model="stall.isAvailable"
                      type="checkbox"
                      class="mr-2 text-blue-600 border-gray-300 rounded"
                    />
                    <span class="text-sm font-medium text-gray-700">Is Available</span>
                  </div>

                  <div class="col-span-2 flex space-x-4 justify-center">
                    <!-- Submit Button -->
                    <button
                      type="submit"
                      class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300"
                    >
                      Enter Stall
                    </button>

                    <!-- Close Button -->
                    <button
                      type="button"
                      @click="closeForm"
                      class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300"
                    >
                      Close
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
export default {
  data() {
    return {
      showForm: false,
      stall: {
        stallName: '',
        stallDescription: '',
        stallDate: '',
        stallLocation: '',
        startTime: '',
        endTime: '',
        contactEmail: '',
        stallBookingCost: '',
        isAvailable: true,
      },
      errors: {}, // Store validation errors
      successMessage: '',
    };
  },
  computed: {
    isFormValid() {
      this.errors = {}; // Clear previous errors

      // Required fields validation
      for (const key in this.stall) {
        if (this.stall[key] === '' || this.stall[key] === null) {
          this.errors[key] = `${key.replace(/([A-Z])/g, ' $1')} is required.`;
        }
      }

      // Validate stall date (must be in the future)
      const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
      if (this.stall.stallDate && this.stall.stallDate <= today) {
        this.errors.stallDate = 'Stall date must be in the future.';
      }

      // Validate start time < end time
      if (this.stall.startTime && this.stall.endTime && this.stall.startTime >= this.stall.endTime) {
        this.errors.startTime = 'Start time must be earlier than end time.';
        this.errors.endTime = 'End time must be later than start time.';
      }

      // Validate email format
      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      if (this.stall.contactEmail && !emailRegex.test(this.stall.contactEmail)) {
        this.errors.contactEmail = 'Enter a valid email address.';
      }

      // Validate stall booking cost (must be a positive number)
      if (this.stall.stallBookingCost <= 0) {
        this.errors.stallBookingCost = 'Booking cost must be greater than zero.';
      }

      return Object.keys(this.errors).length === 0; // Form is valid if no errors exist
    }
  },
  methods: {
    openForm() {
      this.showForm = true;
    },
    closeForm() {
      this.showForm = false;
    },
    async submitForm() {
      if (!this.isFormValid) {
        return;
      }

      try {
        const response = await axios.post('/create-stall', this.stall);
        this.successMessage = response.data.message;
        this.stall = {}; // Clear the form
        setTimeout(() => {
          this.successMessage = '';
          this.closeForm();
        }, 3000);
      } catch (error) {
        console.error('Error creating stall:', error);
      }
    },
  },
};
</script>

<style scoped>
.sx-vue-calendar-wrapper {
  width: 1200px;
  max-width: 100vw;
  height: 800px;
  max-height: 90vh;
}
</style>
