<x-filament::page>
    {{-- <div class="flex justify-end">
        <button class="bg-blue-400 text-white text-sm p-0.5 px-2 rounded">Send Notification</button>
    </div> --}}
    {{-- <form wire:submit.prevent="submit">
        {{ $this->form }}
     
        <button type="submit">
            Submit
        </button>
    </form> --}}
    <div class="grid grid-cols-1 md-grid-cols-3 xl:grid-cols-4 gap-2">
        @forelse ($doctors as $d)
            <div class="bg-white flex items-center border p-4 rounded-lg shadow-md">
                <div class="flex-shrink-0 mr-4">
                <img class="h-16 w-16 rounded-full object-cover" src="{{$d->thumbnail}}" alt="{{$d->full_name}}">
                </div>
                <div>
                <h4 class="font-semibold text-base">{{$d->full_name}}</h4>
                <p class="text-gray-600">
                    @if(count($d->specialities))
                    {{$d->specialities[0]->name}}
                    @elseif(count($d->sub_specialities))
                    {{$d->sub_specialities[0]->name}}
                    @endif
                </p>
                </div>
            </div>
        @empty
            <span class="bg-blue-400 text-white p-2 text-base">No Speciality Presented</span>
        @endforelse
       
          
    </div>
    {{$doctors->links()}}
</x-filament::page>
