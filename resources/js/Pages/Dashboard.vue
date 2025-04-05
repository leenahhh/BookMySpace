<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    profiles: {
      type: Array,
      required: true,
    },

    processedProfiles:{
      type: Array,
      required: true,
    },

    processedStalls:{
      type: Array,
      required: true,
    },

    stalls: {
      type: Array,
      required: true,
    },
});

console.log(JSON.stringify(props.profiles, null, 2));
console.log(JSON.stringify(props.stalls,null, 2));


// Function to update profile reg status
function updateStatus(profile, newStatus) {
  if (newStatus === 'rejected' && (!profile.rejected_reason || profile.rejected_reason.trim() === '')) {
    alert('Please enter a reason for rejection before proceeding.');
    return;
  }

  router.put(`/profile/${profile.id}/status`, { 
    status: newStatus, 
    rejected_reason: profile.rejected_reason 
  }, {
    onSuccess: () => {
      console.log(`Profile ${profile.id} updated to ${newStatus}`);
    },
  });
}

// Function to update booking status
function updateStallStatus(stall, newStatus) {
  if (newStatus === 'rejected' && (!stall.stall_rejected_reason || stall.stall_rejected_reason.trim() === '')) {
    alert('Please enter a reason for booking rejection before proceeding.');
    return;
  }

  router.put(`/stall/${stall.id}/status`, { 
    status: newStatus, 
    stall_rejected_reason: stall.stall_rejected_reason 
  }, {
    onSuccess: () => {
      console.log(`Stall ${stall.id} updated to ${newStatus}`);
    },
  });
}

//for buiness reg
function deleteProfile(profile) {
  if (confirm(`Are you sure you want to delete ${profile.name}?`)) {
    router.delete(`/profile/${profile.id}`, {
      onSuccess: () => {
        console.log(`Profile ${profile.id} deleted`);
      },
    });
  }
}

//for booking reg
function deleteBooking(stall) {
  if (confirm(`Are you sure you want to delete ${stall.name}?`)) {
    router.delete(`/stall/${stall.id}`, {
      onSuccess: () => {
        console.log(`Stall ${stall.id} deleted`);
      },
    });
  }
}

function refreshData() {
  router.reload();
}
</script>

<template>
  <Head title="Home" />

  <AdminLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Welcome Admin!
      </h2>
    </template>

    
    
    <div class="p-6">
    <h2 class="text-3xl font-semibold text-gray-800 mb-6 border-b-2 border-gray-200 pb-2">
      Business Profile Requests
    </h2>
    <!-- Refresh Button -->
    <button @click="refreshData" class="mb-4 p-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
      <i class="fas fa-sync-alt"></i> Refresh
    </button>

    <!-- Table -->
    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
      <thead>
        <tr class="bg-gray-100">
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">ID</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Logo</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Business Name</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Student Name</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Product Type</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Product Decription</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Socials</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Status</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Rejected Reason</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Loop over the business profiles and display each row -->
        <tr v-for="profile in profiles" :key="profile.id" class="border-t">
          <td class="py-2 px-4 text-sm text-gray-700">{{ profile.id }}</td>
          <td class="py-2 px-4 text-sm text-gray-700">
            <img :src="profile.logo" alt="Logo" class="w-12 h-12 rounded-full"/>
          </td>
          <td class="py-2 px-4 text-sm text-gray-700">{{ profile.name }}</td>
          <td class="py-2 px-4 text-sm text-gray-700">{{ profile.user.name }}</td>
          <td class="py-2 px-4 text-sm text-gray-700">{{ profile.product_type }}</td>
          <td class="py-2 px-4 text-sm text-gray-700">{{ profile.desc }}</td>
          <td class="py-2 px-4 text-sm text-gray-700">
            <a :href="profile.socials" target="_blank" class="text-blue-500 hover:underline">
              {{ profile.socials }}
            </a>
          </td>
          <td class="py-2 px-4 text-sm text-gray-700">
            <span :class="{'text-green-500': profile.status === 'accepted', 'text-red-500': profile.status === 'rejected'}">
              {{ profile.status }}
            </span>
          </td>
          <!-- Rejected Reason Column with Input Box -->
          <td class="py-2 px-4 text-sm text-gray-700">
            <input
              v-model="profile.rejected_reason"
              type="text"
              placeholder="Reason for rejected profile"
              class="border border-gray-300 rounded px-2 py-1 w-full text-sm"
            />
          </td>
          <!-- Accept and Reject Buttons -->
          <td class="py-2 px-4 text-sm text-gray-700">
            <!-- Accept Button with margin-right -->
            <button
              @click="updateStatus(profile, 'accepted')"
              class="bg-green-500 text-white rounded px-3 py-1 hover:bg-green-600 transition w-20 mr-2"
            >
              Accept
            </button>
            
            <!-- Reject Button -->
            <button
              @click="updateStatus(profile, 'rejected')"
              class="bg-red-500 text-white rounded px-3 py-1 hover:bg-red-600 transition w-20"
            >
              Reject
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="p-6">
  <h2 class="text-3xl font-semibold text-gray-800 mb-6 border-b-2 border-gray-200 pb-2">
    Stall Requests
  </h2>
  <!-- Table -->
  <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
    <thead>
      <tr class="bg-gray-100">
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">ID</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Business Name</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Entrepreneur Name</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Description</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Date</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Location</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Time</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Email</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Price</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Status</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Rejected Reason</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Loop over the stalls and display each row -->
      <tr v-for="stall in stalls" :key="stall.id" class="border-t">
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.id }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.business_profile.name }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.user.name }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.business_profile.desc }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.stall_date }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.stall_location }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.time_range }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">
          <a :href="'mailto:' + stall.contact_email" class="text-blue-500 hover:underline">
            {{ stall.contact_email }}
          </a>
        </td>
        <td class="py-2 px-4 text-sm text-gray-700 flex items-center space-x-2">
          <span>Rs. {{ stall.stall_price }}</span>
          <a
            :href="stall.receipt"
            :download="`receipt-${stall.id}`"
            target="_blank"
            class="text-blue-600 hover:text-blue-800"
            title="Download Receipt"
          >
            <!-- Heroicon: Download -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
            </svg>
          </a>
        </td>

        <td class="py-2 px-4 text-sm text-gray-700">
          <span
            :class="{
              'text-green-500': stall.status === 'accepted',
              'text-yellow-500': stall.status === 'pending',
              'text-red-500': stall.status === 'rejected'
            }"
          >
            {{ stall.status }}
          </span>
        </td>
        <td class="py-2 px-4 text-sm text-gray-700">
          <input
            v-model="stall.stall_rejected_reason"
            type="text"
            placeholder="Reason for rejection"
            class="border border-gray-300 rounded px-2 py-1 w-full text-sm"
          />
        </td>
        <!-- Accept and Reject Buttons -->
        <td class="py-2 px-4 text-sm text-gray-700">
          <button
            @click="updateStallStatus(stall, 'accepted')"
            class="bg-green-500 text-white rounded px-3 py-1 hover:bg-green-600 transition w-20 mr-2"
          >
            Accept
          </button>
          <button
            @click="updateStallStatus(stall, 'rejected')"
            class="bg-red-500 text-white rounded px-3 py-1 hover:bg-red-600 transition w-20"
          >
            Reject
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</div>

    <div class="p-6">
    <h2 class="text-3xl font-semibold text-gray-800 mb-6 border-b-2 border-gray-200 pb-2">
      Processed Requests
    </h2>

    <!-- Table -->
    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
      <thead>
        <tr class="bg-gray-100">
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">ID</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Logo</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Business Name</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Student Name</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Product Type</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Product Decription</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Socials</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Status</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Rejected Reason</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Loop over the business profiles and display each row -->
        <tr v-for="profile in processedProfiles" :key="profile.id" class="border-t">
          <td class="py-2 px-4 text-sm text-gray-700">{{ profile.id }}</td>
          <td class="py-2 px-4 text-sm text-gray-700">
            <img :src="profile.logo" alt="Logo" class="w-12 h-12 rounded-full"/>
          </td>
          <td class="py-2 px-4 text-sm text-gray-700">{{ profile.name }}</td>
          <td class="py-2 px-4 text-sm text-gray-700">{{ profile.user.name }}</td>
          <td class="py-2 px-4 text-sm text-gray-700">{{ profile.product_type }}</td>
          <td class="py-2 px-4 text-sm text-gray-700">{{ profile.desc }}</td>
          <td class="py-2 px-4 text-sm text-gray-700">
            <a :href="profile.socials" target="_blank" class="text-blue-500 hover:underline">
              {{ profile.socials }}
            </a>
          </td>
          <td class="py-2 px-4 text-sm text-gray-700">
            <span :class="{'text-green-500': profile.status === 'accepted', 'text-red-500': profile.status === 'rejected'}">
              {{ profile.status }}
            </span>
          </td>
          <!-- Rejected Reason Column -->
          <td v-if="profile.status == 'rejected'" class="py-2 px-4 text-sm text-gray-700">{{ profile.rejected_reason }}</td>
          <td v-else class="py-2 px-4 text-sm text-gray-700">None</td>
          <!-- Accept and Reject Buttons -->
          <td class="py-2 px-4 text-sm text-gray-700">
            <!-- Delete Button with margin-right -->
            <button
              @click="deleteProfile(profile)"
              class="bg-red-500 text-white rounded px-3 py-1 hover:bg-red-600 transition w-20 mr-2"
            >
              Delete
            </button>
            
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  

<div class="p-6">
  <h2 class="text-3xl font-semibold text-gray-800 mb-6 border-b-2 border-gray-200 pb-2">
    Confirmed Stall Requests
  </h2>
  <!-- Table -->
  <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
    <thead>
      <tr class="bg-gray-100">
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">ID</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Business Name</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Entrepreneur Name</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Description</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Date</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Location</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Time</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Email</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Price</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Status</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Rejected Reason</th>
        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Loop over the stalls and display each row -->
      <tr v-for="stall in processedStalls" :key="stall.id" class="border-t">
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.id }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.business_profile.name }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.user.name }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.business_profile.desc }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.stall_date }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.stall_location }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.time_range }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">
          <a :href="'mailto:' + stall.contact_email" class="text-blue-500 hover:underline">
            {{ stall.contact_email }}
          </a>
        </td>
        <td class="py-2 px-4 text-sm text-gray-700 flex items-center space-x-2">
          <span>Rs. {{ stall.stall_price }}</span>
          <a
            :href="stall.receipt"
            :download="`receipt-${stall.id}`"
            target="_blank"
            class="text-blue-600 hover:text-blue-800"
            title="Download Receipt"
          >
            <!-- Heroicon: Download -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
            </svg>
          </a>
        </td>

        <td class="py-2 px-4 text-sm text-gray-700">
          <span
            :class="{
              'text-green-500': stall.status === 'accepted',
              'text-yellow-500': stall.status === 'pending',
              'text-red-500': stall.status === 'rejected'
            }"
          >
            {{ stall.status }}
          </span>
        </td>
        <td class="py-2 px-4 text-sm text-gray-700">{{ stall.stall_rejected_reason }}</td>
        <td class="py-2 px-4 text-sm text-gray-700">
          <button
            @click="deleteBooking(stall)"
            class="bg-red-500 text-white rounded px-3 py-1 hover:bg-red-600 transition w-20 mr-2"
          >
            Delete
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</div>




  
    
  </AdminLayout>
</template>

<!-- //   const ProfileRequest = (id)=>{
//     router.post(`/admin/request/${id}`,{},{
//       onSuccess:()=>{
//         console.log("Request sent");
//       }
//     })
//   } -->

<style scoped>
/* Optional styles for the table */
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  border: 1px solid #ddd;
}

th {
  background-color: #f3f4f6;
}

td {
  text-align: left;
}

</style>