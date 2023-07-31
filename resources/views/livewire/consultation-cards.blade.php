<p class="px-4 py-3 bg-gray-100  dark:bg-[#1f2937]">
    <span class="font-medium">
        Doctor:
    </span>
    <a class="underline underline-offset-2" target="_blank" href="{{route('filament.resources.doctors.view',['record'=>$getRecord()->doctor->id ])}}">
        {{ $getRecord()->doctor->full_name }}
    </a>
</p>
<p class="px-4 py-3 bg-gray-100  dark:bg-[#1f2937]">
    <span class="font-medium">
        Patient:
    </span>
    <a class="underline underline-offset-2" target="_blank" href="{{route('filament.resources.patients.view',['record'=>$getRecord()->patient->id ])}}">
        {{ $getRecord()->patient->full_name }}
    </a>
</p> 
<p class="px-4 py-3 bg-gray-100  dark:bg-[#1f2937]">
    <span class="font-medium">
        status:
    </span>
    @php
       
        switch ($getRecord()->status) {
            case 'canceled':
                $class='bg-orange-500 text-white p-1 py-0.5 text-sm rounded';
                break;
            case 'rejected':
                $class='bg-red-500 text-white p-1 py-0.5 text-sm rounded';
                break;
            case 'pending':
                $class='bg-blue-300 text-white p-1 py-0.5 text-sm rounded';
                break;
            case 'in_progress':
                $class='bg-blue-500 text-white p-1 py-0.5 text-sm rounded';
                break;
            case 'incompleted':
                $class='bg-lime-500 text-white p-1 py-0.5 text-sm rounded';
                break;
            case 'completed':
                $class='bg-success-500 text-white p-1 py-0.5 text-sm rounded';
                break;
            
            default:
                $class="";
                break;
        }
    @endphp
    <span class=" {{$class}}">
        {{ $getRecord()->status }}
    </span>
</p> 
<p class="px-4 py-3 bg-gray-100 dark:bg-[#1f2937]">
    <span class="font-medium">
        Date:
    </span>
    <span>
        {{ date('d-m-Y',strtotime($getRecord()->updated_at)) }}
    </span>
</p> 