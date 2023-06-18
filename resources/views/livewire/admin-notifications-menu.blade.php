<div class="relative" x-data="{open:false}">
    <!-- Notification Bell Icon -->
    <button @click="open=!open" class="ml-3 rtl:mr-3 relative flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full">
        <svg class="h-5 w-5 text-gray-800 dark:text-red-500" width="64px" height="64px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title></title> <g id="Complete"> <g id="bell"> <g> <path d="M18.9,11.2s0-8.7-6.9-8.7-6.9,8.7-6.9,8.7v3.9L2.5,17.5h19l-2.6-2.4Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path> <path d="M14.5,20.5s-.5,1-2.5,1-2.5-1-2.5-1" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path> </g> </g> </g> </g></svg>
    </button>
  
    <!-- Notification Dropdown -->
    <div x-show="open" @click.outside="open=false" class="absolute right-0 w-64 mt-2 bg-white rounded-md shadow-lg">
      <div class="py-2">
        <!-- Notification Item -->
        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">Notification 1</a>
  
        <!-- Notification Item -->
        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">Notification 2</a>
  
        <!-- Notification Item -->
        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">Notification 3</a>
  
        <!-- Notification Item -->
        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">Notification 4</a>
      </div>
  
      <!-- View All Notifications Button -->
      <div class="px-4 py-2 bg-gray-100">
        <a href="#" class="block text-sm text-center text-blue-500 hover:underline">View All Notifications</a>
      </div>
    </div>
  </div>
