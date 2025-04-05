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

    user_stall: {
      type: Array,
      required: true,
    },
});


console.log(JSON.stringify(props.user_stall,null, 2));

// State
const showForm = ref(false)
const successMessage = ref('')
const errors = ref({})
const showPaymentModal = ref(false) // new state

// Reactive form object
const stall = reactive({
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
    console.log(stall);
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

  <div class="flex flex-col lg:flex-row gap-6 p-6">

    <!-- Weekly Stall Timings (1/4) -->
    <div class="flex flex-col w-full px-6 space-y-4">
      <h2 class="text-2xl font-bold text-gray-800 text-center">Weekly Stall Timings</h2>

      <!-- Flex wrapper for the weekday cards -->
      <div class="flex flex-wrap gap-4">
        <div
          v-for="weekday in availableDates"
          :key="weekday.weekday"
          class="flex-1 min-w-[280px] bg-blue-100 p-4 rounded-lg shadow-md"
        >
          <h3 class="text-lg font-semibold text-gray-800">{{ weekday.weekday }}</h3>

          <div class="flex flex-col gap-4 mt-3">
            <!-- Main Hall -->
            <div class="bg-white p-3 rounded-lg shadow">
              <h4 class="text-sm font-semibold text-gray-700">Main Hall</h4>
              <ul class="mt-2 text-xs text-gray-600 space-y-1">
                <li>8:00 AM - 10:00 AM</li>
                <li>10:15 AM - 12:15 PM</li>
                <li>3:00 PM - 5:00 PM</li>
              </ul>
            </div>

            <!-- Outdoor Area -->
            <div class="bg-white p-3 rounded-lg shadow">
              <h4 class="text-sm font-semibold text-gray-700">Outdoor Area</h4>
              <ul class="mt-2 text-xs text-gray-600 space-y-1">
                <li>8:00 AM - 10:00 AM</li>
                <li>10:15 AM - 12:15 PM</li>
                <li>3:00 PM - 5:00 PM</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirmed Stall Requests (3/4) -->
    <div class="lg:w-80 w-full max-h-72">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-2">
        Stall Bookings
      </h2>

      <div class="flex flex-col overflow-y-auto max-h-full gap-6">
        <div
          v-if="user_stall.length > 0"
          v-for="stall in user_stall"
          :key="stall.id"
          class="bg-white shadow-md rounded-xl p-5 border border-gray-100 w-full"
        >
          <div class="mb-3">
            <h3 class="text-xl font-bold text-gray-800">{{ stall.business_profile.name }}</h3>
            <p class="text-sm text-gray-500">By {{ stall.user.name }}</p>
          </div>
          <p class="text-sm text-gray-700 mb-2"><strong>Description:</strong> {{ stall.business_profile.desc }}</p>
          <p class="text-sm text-gray-700 mb-1"><strong>Date:</strong> {{ stall.stall_date }}</p>
          <p class="text-sm text-gray-700 mb-1"><strong>Location:</strong> {{ stall.stall_location }}</p>
          <p class="text-sm text-gray-700 mb-1"><strong>Time:</strong> {{ stall.time_range }}</p>
          
          <div class="flex items-center justify-between mt-3">
            <div class="flex items-center space-x-2">
              <span class="text-sm font-semibold text-gray-800">Rs. {{ stall.stall_price }}</span>
              <a
                :href="stall.receipt"
                :download="`receipt-${stall.id}`"
                target="_blank"
                class="text-blue-600 hover:text-blue-800"
                title="Download Receipt"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                </svg>
              </a>
            </div>
            <span
              class="text-xs font-bold uppercase px-3 py-1 rounded-full"
              :class="{
                'bg-green-100 text-green-700': stall.status === 'accepted',
                'bg-yellow-100 text-yellow-700': stall.status === 'pending',
                'bg-red-100 text-red-700': stall.status === 'rejected'
              }"
            >
              {{ stall.status }}
            </span>
          </div>
          <p v-if="stall.stall_rejected_reason" class="text-xs text-red-500 mt-2">
            <strong>Rejection Reason:</strong> {{ stall.stall_rejected_reason }}
          </p>
        </div>
        <div v-else>
          <p>No Bookings placed</p>
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


  </AuthenticatedLayout>
</template>

