<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive } from 'vue'
import axios from 'axios'
import { Inertia } from '@inertiajs/inertia'


const props = defineProps({
    accepted_reg: {
      type: Boolean,
      required: true,
    },
});

// State
const showForm = ref(false)
const successMessage = ref('')
const errors = ref({})
const showPaymentModal = ref(false) // new state

// Reactive form object
const stall = reactive({
  stallName: '',
  stallDescription: '',
  stallDate: '',
  stallLocation: '',
  timeRange: '',
  contactEmail: '',
})

// Methods
const openForm = () => {
  showForm.value = true
}

const closeForm = () => {
  showForm.value = false
}
const receiptFile = ref(null)

const handleReceiptUpload = (e) => {
  receiptFile.value = e.target.files[0]
}

const submitForm = async () => {
  try {
    const response = await axios.post('/stalls', { ...stall })

    if (response.data.success) {
      successMessage.value = response.data.message
      showPaymentModal.value = true
    }
    // const response = await axios.post('/stalls', { ...stall })

    // successMessage.value = response.data.success

    // Clear form
    // stall.stallName = ''
    // stall.stallDescription = ''
    // stall.stallDate = ''
    // stall.stallLocation = ''
    // stall.timeRange = ''
    // stall.contactEmail = ''
    // errors.value = {}
    // Open the payment receipt modal
    showPaymentModal.value = true
  } catch (error) {
    if (error.response) {
      if (error.response.status === 422) {
        errors.value = error.response.data.errors
      } else if (error.response.status === 409) {
        alert(error.response.data.error) // ❗ Show overlap error
      }
    }
  }
}

const submitPaymentReceipt = () => {
  if (!receiptFile.value) {
    alert('Please upload a payment receipt.')
    return
  }

  const formData = new FormData()
  formData.append('receipt', receiptFile.value)
  formData.append('stallName', stall.stallName)
  formData.append('stallDescription', stall.stallDescription)
  formData.append('stallDate', stall.stallDate)
  formData.append('stallLocation', stall.stallLocation)
  formData.append('timeRange', stall.timeRange)
  formData.append('contactEmail', stall.contactEmail)

  Inertia.post('/stallPayment', formData, {
    preserveScroll: true,
    onSuccess: () => {
      alert('Payment receipt submitted successfully!')
      showPaymentModal.value = false

      // Clear form
      Object.keys(stall).forEach(key => (stall[key] = ''))
      receiptFile.value = null
    },
    onError: (errors) => {
      console.error(errors)
      alert('Failed to submit payment receipt. Please check your input.')
    }
  })
}


const weeks = 4;
const weekdays = ['Tuesday', 'Wednesday', 'Thursday'];

// Method to generate the dates
const generateAvailableDates = () => {
  const currentDate = new Date();
  const availableDates = [];

  // Helper function to get the next date for a given weekday
  const getNextWeekday = (dayIndex) => {
    const currentDay = currentDate.getDay();
    const diff = (dayIndex - currentDay + 7) % 7; // Days difference
    const nextDate = new Date(currentDate);
    nextDate.setDate(currentDate.getDate() + diff);
    return nextDate;
  };

  // For each weekday (Tuesday, Wednesday, Thursday), get the next 4 occurrences
  weekdays.forEach((weekday, index) => {
    let dayDates = [];
    for (let i = 0; i < weeks; i++) {
      let nextDate = getNextWeekday(index + 2); // Get next weekday (2 = Tuesday)
      nextDate.setDate(nextDate.getDate() + i * 7); // Add 1 week for subsequent dates
      dayDates.push(nextDate.toISOString().split('T')[0]); // Get the date in YYYY-MM-DD format
    }
    availableDates.push({ weekday, dates: dayDates });
  });

  return availableDates;
};

// Store available dates
const availableDates = generateAvailableDates();

</script>

<template>
  <Head title="Home" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Welcome Entrepreneur!
      </h2>
    </template>
    
    <div class="flex justify-center items-center h-40 bg-gray-900">
      <div v-if="accepted_reg" class="flex items-center gap-6">
        <h2 class="text-3xl font-bold text-white">Book your stalls</h2>
        <button
        @click="openForm"
        class="text-white py-3 px-6 rounded-2xl bg-cover bg-center shadow-lg hover:scale-105 transition-transform duration-300 text-2xl font-bold"
        style="background-image: url('/images/button.png');"
        >
        here
      </button>
      </div>
      <div v-else>
        <h2 class="text-3xl font-bold text-white">Your Business is still pending approval</h2>
      </div>
    </div>
    
    
    
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div>
            <div class="p-6 space-y-6">
              <div class="flex flex-col space-y-4">
                  <h2 class="text-2xl font-bold text-center text-gray-800">Weekly Stall Timings</h2>
                  
                  <!-- Tuesday Section -->
                  <div class="bg-blue-100 p-6 rounded-lg shadow-md">
                      <h3 class="text-xl font-semibold text-gray-800">Tuesdays</h3>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                          
                          <!-- Main Hall -->
                          <div class="bg-white p-4 rounded-lg shadow-lg">
                              <h4 class="text-lg font-semibold text-gray-700">Main Hall</h4>
                              <ul class="space-y-2 mt-2 text-gray-600">
                                  <li>8:00 AM - 10:00 AM</li>
                                  <li>10:15 AM - 12:15 PM</li>
                                  <li>3:00 PM - 5:00 PM</li>
                              </ul>
                          </div>
                          
                          <!-- Outdoor Area -->
                          <div class="bg-white p-4 rounded-lg shadow-lg">
                              <h4 class="text-lg font-semibold text-gray-700">Outdoor Area</h4>
                              <ul class="space-y-2 mt-2 text-gray-600">
                                  <li>8:00 AM - 10:00 AM</li>
                                  <li>10:15 AM - 12:15 PM</li>
                                  <li>3:00 PM - 5:00 PM</li>
                              </ul>
                          </div>
                      </div>
                  </div>

                  <!-- Wednesday Section -->
                  <div class="bg-blue-100 p-6 rounded-lg shadow-md">
                      <h3 class="text-xl font-semibold text-gray-800">Wednesdays</h3>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                          
                          <!-- Main Hall -->
                          <div class="bg-white p-4 rounded-lg shadow-lg">
                              <h4 class="text-lg font-semibold text-gray-700">Main Hall</h4>
                              <ul class="space-y-2 mt-2 text-gray-600">
                                  <li>8:00 AM - 10:00 AM</li>
                                  <li>10:15 AM - 12:15 PM</li>
                                  <li>3:00 PM - 5:00 PM</li>
                              </ul>
                          </div>
                          
                          <!-- Outdoor Area -->
                          <div class="bg-white p-4 rounded-lg shadow-lg">
                              <h4 class="text-lg font-semibold text-gray-700">Outdoor Area</h4>
                              <ul class="space-y-2 mt-2 text-gray-600">
                                  <li>8:00 AM - 10:00 AM</li>
                                  <li>10:15 AM - 12:15 PM</li>
                                  <li>3:00 PM - 5:00 PM</li>
                              </ul>
                          </div>
                      </div>
                  </div>
                  
                  <!-- Thursday Section -->
                  <div class="bg-blue-100 p-6 rounded-lg shadow-md">
                      <h3 class="text-xl font-semibold text-gray-800">Thursdays</h3>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                          
                          <!-- Main Hall -->
                          <div class="bg-white p-4 rounded-lg shadow-lg">
                              <h4 class="text-lg font-semibold text-gray-700">Main Hall</h4>
                              <ul class="space-y-2 mt-2 text-gray-600">
                                  <li>8:00 AM - 10:00 AM</li>
                                  <li>10:15 AM - 12:15 PM</li>
                                  <li>3:00 PM - 5:00 PM</li>
                              </ul>
                          </div>
                          
                          <!-- Outdoor Area -->
                          <div class="bg-white p-4 rounded-lg shadow-lg">
                              <h4 class="text-lg font-semibold text-gray-700">Outdoor Area</h4>
                              <ul class="space-y-2 mt-2 text-gray-600">
                                  <li>8:00 AM - 10:00 AM</li>
                                  <li>10:15 AM - 12:15 PM</li>
                                  <li>3:00 PM - 5:00 PM</li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
          </div>



            <!-- Modal for stall creation -->
            <div v-if="showForm" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
              <!-- Success Notification -->
              <div v-if="successMessage" class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white py-2 px-4 rounded-lg shadow-md">
                {{ successMessage }}
              </div>

              <div class="bg-white p-6 rounded-lg w-11/12 max-w-md shadow-lg max-h-screen overflow-y-auto">
                <form @submit.prevent="submitForm" class="grid grid-cols-2 gap-4">
                  <!-- Stall Name -->
                  <div class="mb-4 col-span-2">
                    <label for="stallName" class="block text-sm font-medium text-gray-700">Stall Name</label>
                    <input v-model="stall.stallName" id="stallName" type="text" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md" />
                    <p v-if="errors.stallName" class="text-red-500 text-sm">{{ errors.stallName }}</p>
                  </div>

                  <!-- Stall Description -->
                  <div class="mb-4 col-span-2">
                    <label for="stallDescription" class="block text-sm font-medium text-gray-700">Stall Description</label>
                    <textarea v-model="stall.stallDescription" id="stallDescription" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>
                    <p v-if="errors.stallDescription" class="text-red-500 text-sm">{{ errors.stallDescription }}</p>
                  </div>

                  <!-- Stall Date -->
                  <!-- <div class="mb-4 col-span-2">
                    <label for="stallDate" class="block text-sm font-medium text-gray-700">Stall Date</label>
                    <input 
                      type="date" 
                      v-model="stall.stallDate" 
                      id="stallDate" 
                      required 
                      class="mt-1 block w-full p-2 border border-gray-300 rounded-md" 
                    />
                    <p v-if="errors.stallDate" class="text-red-500 text-sm">{{ errors.stallDate }}</p>
                  </div> -->

                  <!-- Works-Stall date -->
                  <div class="mb-4 col-span-2">
                    <label for="stallDate" class="block text-sm font-medium text-gray-700">Stall Date</label>
                    <select v-model="stall.stallDate" id="stallDate" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                      <option disabled value="">Select Date</option>

                      <!-- Loop through the available dates for each weekday -->
                      <optgroup v-for="weekday in availableDates" :key="weekday.weekday" :label="weekday.weekday">
                        <option v-for="date in weekday.dates" :key="date" :value="date">
                          {{ date }}
                        </option>
                      </optgroup>
                    </select>
                    <p v-if="errors.stallDate" class="text-red-500 text-sm">{{ errors.stallDate }}</p>
                  </div>

                  
                  <!-- Stall Location -->
                  <div class="mb-4 col-span-2">
                    <label for="stallLocation" class="block text-sm font-medium text-gray-700">Stall Location</label>
                    <select v-model="stall.stallLocation" id="stallLocation" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                      <option disabled value="">Select Location</option>
                      <option value="Level 4">Level 4</option>
                      <option value="Level 6">Level 6</option>
                    </select>
                    <p v-if="errors.stallLocation" class="text-red-500 text-sm">{{ errors.stallLocation }}</p>
                  </div>

                  <!-- Stall Price (depends on location) -->
                  <div class="col-span-2 mb-2 text-left">
                    <p v-if="stall.stallLocation === 'Level 4'" class="text-green-600 text-sm">Price for Level 4: LKR 1500</p>
                    <p v-if="stall.stallLocation === 'Level 6'" class="text-green-600 text-sm">Price for Level 6: LKR 3000</p>
                  </div>

                  <!-- Time Range works-->
                  <div class="mb-4 col-span-2">
                    <label for="timeRange" class="block text-sm font-medium text-gray-700">Time Range</label>
                    <select v-model="stall.timeRange" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                      <option disabled value="">Select Time Range</option>
                      <option value="9:00 AM - 11:00 AM">9:00 AM - 11:00 AM</option>
                      <option value="12:00 AM - 2:00 PM">12:00 AM - 2:00 PM</option>
                      <option value="3:00 PM - 5:00 PM">3:00 PM - 5:00 PM</option>
                    </select>
                    <p v-if="errors.timeRange" class="text-red-500 text-sm">{{ errors.timeRange }}</p>
                  </div>


                  <!-- Contact Email -->
                  <div class="mb-4 col-span-2">
                    <label for="contactEmail" class="block text-sm font-medium text-gray-700">Contact Email</label>
                    <input v-model="stall.contactEmail" type="email" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md" />
                    <p v-if="errors.contactEmail" class="text-red-500 text-sm">{{ errors.contactEmail }}</p>
                  </div>

                  <!-- Submit and Close Buttons -->
                  <div class="col-span-2 flex space-x-4 justify-center">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">Send Stall Request</button>
                    <button type="button" @click="closeForm" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">Close</button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Payment Receipt Modal -->
            <div v-if="showPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
              <div class="bg-white p-6 rounded-2xl w-11/12 max-w-md shadow-2xl max-h-screen overflow-y-auto animate-fade-in">
                
                <!-- Modal Title -->
                <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Upload Payment Receipt</h2>

                <!-- Booking Details Summary -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4 shadow-sm space-y-1 text-sm text-gray-700">
                  <p><span class="font-semibold">Name:</span> {{ stall.stallName }}</p>
                  <p><span class="font-semibold">Description:</span> {{ stall.stallDescription }}</p>
                  <p><span class="font-semibold">Date:</span> {{ stall.stallDate }}</p>
                  <p><span class="font-semibold">Location:</span> {{ stall.stallLocation }}</p>
                  <p><span class="font-semibold">Time Slot:</span> {{ stall.timeRange }}</p>
                  <p><span class="font-semibold">Email:</span> {{ stall.contactEmail }}</p>
                  <p v-if="stall.stallLocation === 'Level 4'" class="text-green-600 font-semibold">Total: LKR 1500</p>
                  <p v-if="stall.stallLocation === 'Level 6'" class="text-green-600 font-semibold">Total: LKR 3000</p>
                </div>

                <!-- Receipt Upload Form -->
                <form @submit.prevent="submitPaymentReceipt" class="space-y-4">
                  <!-- Receipt Upload -->
                  <div>
                    <label for="receipt" class="block text-sm font-medium text-gray-700 mb-1">Payment Receipt (Image or PDF)</label>
                    <input
                      type="file"
                      id="receipt"
                      @change="handleReceiptUpload"
                      accept=".jpg,.jpeg,.png,.pdf"
                      required
                      class="block w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-300 focus:outline-none"
                    />
                  </div>

                  <!-- Buttons -->
                  <div class="flex justify-center gap-4 pt-2">
                    <button
                      type="submit"
                      class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition duration-300"
                    >
                      Submit Receipt
                    </button>
                    <button
                      type="button"
                      @click="showPaymentModal = false"
                      class="bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition duration-300"
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
    </div>

  </AuthenticatedLayout>
</template>

