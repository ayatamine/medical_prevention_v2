<x-filament::page>

    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4 flex justify-between items-center">
           <span> Notifications List</span>
            <a href="{{route('admin.notification.mark-as-readAll')}}" class="flex gap-2 items-center text-sm bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                <svg class="h-5 w-5"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M7 12l5 5l10 -10" />  <path d="M2 12l5 5m5 -5l5 -5" /></svg>
                <span>Mark All as Read</span>
            </a>
        </h1>
        <div class="grid grid-cols-1 gap-3 my-6">
          @forelse ($notifications as $notification)
          <div class="bg-white @if($notification->read_at) opacity-50 @endif shadow p-4 py-2 flex items-center justify-between">
            <div class="flex items-center">
              {{-- <div class="rounded-full h-8 w-8 bg-gray-300"></div> --}}
              <div class="ml-4">
                <p class="font-bold">{{$notification->type}}</p>
                <p class="text-gray-500">
                    {{$notification->data['message']}}
                    @if($notification->type =='App\Notifications\NewDoctorRegisteration')
                    <a class="text-blue-600 underline underline-offset-2" href="{{route('filament.resources.doctors.index')}}" class="mx-3">View Doctors</a>
                    @elseif($notification->type =='App\Notifications\NewPatientsRegisteration')
                    <a class="text-blue-600 underline underline-offset-2" href="{{route('filament.resources.patients.index')}}" class="mx-3">View Patients</a>
                    @endif
                </p>
              </div>
            </div>
          @if(!$notification->read_at)
          <a href="{{route('admin.notification.mark-as-read',['id'=>$notification->id])}}"  class="text-sm bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
            Mark as Read
          </a>
          @endif
          </div>
          @empty
          <div class="bg-white  shadow p-4 py-2 flex items-center justify-between">
            <div class="flex items-center">
              {{-- <div class="rounded-full h-8 w-8 bg-gray-300"></div> --}}
              <div class="ml-4">
                no notification found
              </div>
            </div>
          </div>
          @endforelse
         
          <!-- Add more notifications with buttons -->
        </div>
    </div>
</x-filament::page>
