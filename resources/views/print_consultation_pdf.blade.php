<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Medical Prescription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        p {
            margin-bottom: 4px;
        }

        ul {
            list-style-type: disc;
            margin-left: 20px;
        }

        img {
            max-width: 100px;
            height: auto;
        }

        .text-center {
            text-align: center;
        }

        .mt-8 {
            margin-top: 8px;
        }

        .px-4 {
            padding-left: 16px;
            padding-right: 16px;
        }

        .py-2 {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .bg-blue-500 {
            background-color: #3b82f6;
        }

        .text-white {
            color: #ffffff;
        }

        .rounded {
            border-radius: 4px;
        }
    </style>
    <style> 
        * { font-family: DejaVu Sans, sans-serif; }
       </style>
</head>

<body>
    
    <!-- Agency Logo -->
    <div style="text-align:right">
        {{-- <img height="400px;width:550px;float:right;" src="{{$settings->app_logo}}" alt="{{$settings->app_name}}"> --}}
        {{-- <img src="data:image/png;base64,{{ base64_encode(file_get_contents($settings->app_logo)) }}" alt="{{$settings->app_name}}"> --}}
        <div style="float:right;height:100px;width:250px;background-image:url({{$settings->app_logo}});background-size:cover;background-position:center"></div>
    </div>
 <br><br>
    <!-- Prescription Header -->
    <div style="margin-bottom: 20px !important;clear:both;">
        <div style="float: left; width: 50%">
            <p><strong style="font-size: 16px">Patient ID:</strong> {{$consult->patient->id}}</p>
            <p><strong style="font-size: 16px">Patient Name:</strong> {{$consult->patient->full_name}}</p>
            <p><strong style="font-size: 16px">Consultation ID:</strong> {{$consult->id}}</p>
            <p><strong style="font-size: 16px">Date Of Birth:</strong> {{$consult->patient->birth_date}}</p>
            <p><strong style="font-size: 16px">Prescription Date:</strong> {{$consult->created_at}}</p>
        </div>
        <div style="float: right;direction:rtl;text-align:right; width: 50%">
            <p>المملكة العربية السعودية</p>
            <p>ترخيص وزارة  الصحة {{$settings->app_name}}</p>
            <p> السجل التجاري {{$settings->commercial_register}}</p>
            <p> الموقع الالكتروني{{$settings->website_link}}</p>
            <p> العنوان البريدي{{$settings->post_address}}</p>
            <p> رقم خدمة العملاء{{$settings->customer_service_number}}</p>
        </div>
    </div>
    <h5 style="color:red;font-weight:bold;margin:25px 0;clear:both;">
        For minors , All medications must be dispensed in the present of a legal guardian
    </h5>
    <!-- Patient Information -->
    <div>
        <h2 style="background-color: gainsboro;padding:15px 8px;margin-bottom:12px">Diagnosis:</h2>
        <p><strong style="font-size: 16px">Doctor Name:</strong> {{$consult->doctor->name}}</p>
        <p><strong style="font-size: 16px">Medical Licence:</strong> {{$consult->doctor->medical_licence_file}}</p>

        <!-- Add more patient details as needed -->
    </div>

    <!-- Prescription Details -->
    <div>
        <h2 style="background-color: gainsboro;padding:15px 8px;margin-bottom:12px">Prescription:</h2>
        <p><strong style="font-size: 16px">Doctor Name:</strong> {{$consult->doctor->full_name}}</p>
        <p><strong style="font-size: 16px">Medical Licence:</strong> {{$consult->doctor->medical_licence_file}}</p>


    </div>
    <h2 style="background-color: gainsboro;padding:15px 8px;margin-bottom:12px">Symptomes:</h2>
    @forelse ($consult->summary->symptomes as $key=>$symptomes)
    <p><strong style="font-size: 16px">Name:</strong> {{$symptomes->name}}</p> 
    @empty
    <div>
        
        <p>No Symptome found</p>
    </div>
    @endforelse
    @forelse ($consult->summary->medicines as $key=>$med)
    <h2 style="background-color: gainsboro;padding:15px 8px;margin-bottom:12px">{{$key}}: {{$med->drug_name}}</h2>
    <p><strong style="font-size: 16px">Drug Name:</strong> {{$med->drug_name}}</p> 
    <div >
        <div style="float: left; width: 50%">
            <p><strong style="font-size: 16px">Dose/Unit:</strong> {{$med->dose}} / {{$med->unit}}</p>
        </div>
        <div style="float: right; width: 50%">
            <p><strong style="font-size: 16px">Route:</strong> {{$med->route}}</p>
        </div>
    </div>
    <div style="clear: both">
        <div style="float: left; width: 50%" >
            <p><strong style="font-size: 16px">Frequency:</strong> {{$med->frequancy}}</p>
        </div>
        <div style="float: right; width: 50%">
            <p><strong style="font-size: 16px">duration:</strong> {{$med->duration}} days</p>
        </div>
    </div>
    @empty
    <div>
        
        <p>No Medicine found</p>


    </div>
    @endforelse
    <br>
   
    <br><br><br>
    <div style="text-align:right;margin-top:40px;clear:both;">
        {{-- <img height="400px;width:550px;float:right;" src="{{$settings->app_logo}}" alt="{{$settings->app_name}}"> --}}
        {{-- <img src="data:image/png;base64,{{ base64_encode(file_get_contents($settings->app_logo)) }}" alt="{{$settings->app_name}}"> --}}
        <div style="float:right;height:100px;width:250px;background-image:url({{$settings->signature_image}});background-size:cover;background-position:center"></div>
    </div>


</body>

</html>