<?php

namespace App\Repositories;

use PDF;
use Exception;
use App\Models\Drug;
use App\Models\Doctor;
use App\Models\Rating;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\Summary;
use App\Models\Consultation;
use App\Models\PatientScale;
use App\Models\ScaleQuestion;
use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DoctorReviewAdded;
use App\Http\Resources\PatientScaleResource;
use App\Notifications\ConsultationStatusUpdated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class ConsultationRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Consultation::class;
    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'created_at',
    ];
    /**
     * approve a consultation request
     *
     */
    public function approveConuslt($consultation_id)
    {
        $consultation = request()->user()->consultations()
        ->where('status', 'pending')
        ->findOrFail($consultation_id);


        $patient =Patient::findOrFail($consultation->patient_id);

        $consultation->update(['status'=>'in_progress']);

        $data=array(
            'message'=>'your consultation #'.$consultation_id.' with the doctor '.request()->user()->full_name.' has been approved',
            'consultation_id'=>$consultation_id,
           'patient'=>$patient,
           'status'=>'in_progress'
        );

        $patient->notify((new ConsultationStatusUpdated($data)));

        return $patient;
    }
    /**
     * reject a consultation request
     *
     */
    public function rejectConsult($consultation_id)
    {
        $consultation = request()->user()->consultations()
        ->where('status', 'pending')
        ->findOrFail($consultation_id);

        $patient =Patient::findOrFail($consultation->patient_id);
        $consultation->update(['status' => 'rejected']);
        $data=array(
            'message'=>'your consultation #'.$consultation_id.' with the doctor '.request()->user()->full_name.' has bee rejected',
            'consultation_id'=>$consultation_id,
           'patient'=>$patient,
           'status'=>'rejected'
        );
        $patient->notify((new ConsultationStatusUpdated($data)));
       return $patient;
    }
    /**
     * reject a consultation request
     *
     */
    public function finishConsult($consultation_id)
    {
        $consultation = request()->user()->consultations()->where('status', 'in_progress')->findOrFail($consultation_id);

        $patient =Patient::findOrFail($consultation->patient_id);
        $consultation->update(['status' => 'incompleted']);
        $data=array(
            'message'=>'your consultation #'.$consultation_id.' with the doctor '.request()->user()->full_name.' has been completed, waiting for the doctor summary',
            'consultation_id'=>$consultation_id,
            'patient'=>$patient,
            'status'=>'incompleted'
        );
        $patient->notify((new ConsultationStatusUpdated($data)));

        return $patient;
        //incompleted means need summary

    }
    /**
     * add a summary to a consultation
     *
     */
    public function addSummary($request, $consultation_id)
    {

        // $consultation =  request()->user()->consultations()->where('status', 'pending')->findOrFail($consultation_id);
        //  //TODO: send notification to patient
        $consultation = Consultation::where('status','incompleted')->findOrFail($consultation_id);
        $summary = Summary::where('consultation_id',$consultation_id)->first();
        if($summary) return 'exists';
            DB::transaction(function () use ($consultation,$consultation_id,$request) {
                $summary = Summary::create([
                    'description' => $request['description'],
                    'sick_leave' => $request['sick_leave'],
                    'other_lab_tests' => $request['other_lab_tests'] ? json_encode($request['other_lab_tests']) :null,
                    'notes' => $request['notes'] ?? null,
                    // 'prescription' =>$request['prescription'] ? json_encode($request['prescription']) :null,
                    'consultation_id' => $consultation_id,
                    'medicines'=>json_encode($request['medicines']),
                    'other_medicines'=>array_key_exists('other_medicines',$request) ? json_encode($request['other_medicines']) : null,
                ]);
                if(array_key_exists('lab_tests',$request))
                {
                    if(is_array($request['lab_tests']))
                    {
                     $summary->labTests()->sync($request['lab_tests']);
                    }
                }
                // if(array_key_exists('medicines',$request))
                // {
                //     if(is_array($request['medicines']))
                //     {
                //      $summary->medicines()->sync($request['medicines']);
                //     }
                // }
                if(array_key_exists('sub_specialities',$request))
                {
                    if(is_array($request['sub_specialities']))
                    {
                     $summary->subSpecialities()->sync($request['sub_specialities']);
                    }
                }
                if(array_key_exists('symptomes',$request))
                {
                    if(is_array($request['symptomes']))
                    {
                     $summary->symptomes()->sync($request['symptomes']);
                    }
                }
                //mark as completed
                $consultation->update(['status' => 'completed','finished_at'=>now()]);
                //notify patient
                $patient =Patient::findOrFail($consultation->patient_id);
                    $data=array(
                        'message'=>'your consultation #'.$consultation_id.' with the doctor '.request()->user()->full_name.' has been updated with the summary',
                        'consultation_id'=>$consultation_id,
                       'patient'=>$patient,
                       'status'=>'completed'
                    );
                $patient->notify((new ConsultationStatusUpdated($data)));
                return $summary;
            });
    }
    /** view a summary */
    public function viewSummary($consultation_id)
    {
        return Consultation::whereIn('status',['incompleted','completed'])->with('doctor:id,full_name,thumbnail','patient:id,full_name','summary','review')->findOrFail($consultation_id);
    }
    /** print a summary */
    public function printSummary($consultation_id)
    {
        $consult = Consultation::whereIn('status',['incompleted','completed'])->with('doctor:id,full_name','patient:id,full_name,birth_date,thumbnail','summary','review')->findOrFail($consultation_id);
        // $summmary_pdf = new Dompdf();
        // $summmary_pdf->loadHtml('hello world');
        $settings = Setting::firstOrFail();

        // dd($settings->app_logo);
        $pdf = PDF::loadView('print_consultation_pdf', ['consult'=>$consult,'settings'=>$settings])->set_option('isRemoteEnabled', true);
        $pdfContent = $pdf->output();
        $filePath = "/consultations/pdf/consultation_#$consult->id.pdf";
        // file_put_contents($filePath, $pdfContent);

        Storage::disk('public')->put($filePath,$pdfContent);

        // file_put_contents($filePath, $pdfContent);
        return url('storage',$filePath);
        // $pdf->render();
// return $pdf->stream('sdfsdf.pdf');
        // return  $pdf->download("consultation_#$summary->id.pdf");



    }
    /**
     * fetch patient medical record
     */
    public function patientMedicalRecord($consultation_id)
    {
        $consult = Consultation::with('patient',  "patient.allergies","patient.family_histories", "patient.chronic_diseases")
            ->findOrFail($consultation_id);

        $anexiety_scale = $this->patientScales($consult->patient->id, 1);
        $depression_scale = $this->patientScales($consult->patient->id, 2);
        return array('consult' => $consult, 'anexiety_scale' => $anexiety_scale, 'depression_scale' => $depression_scale);
    }
    public function patientScales($id, $scale_id)
    {
        $scales = PatientScale::wherePatientId($id)
            ->with(['scaleQuestion' => function ($q) {
                $q->select(['id', 'question', 'question_ar', 'scale_id']);
            }])
            ->whereHas('scaleQuestion', function ($q) use ($scale_id) {
                $q->whereScaleId($scale_id);
            })
            ->get()
            ->map(function ($q) {
                return new PatientScaleResource($q);
            });

        return $scales;
    }
    /**
     * add a review to a consultation
     */
    public function addReview($request, $consultation_id)
    {

        $consultation = Consultation::findOrFail($consultation_id);
        if( Rating::where('consultation_id',$consultation_id)->first())  return response()->json(
            [
                'success'=>false,
                'message'=>'you  have already rated this consultation'
            ]
            );
        if ($consultation->status == 'completed' || $consultation->status == 'incompleted') {
            Rating::firstOrCreate([ 'consultation_id' => $consultation_id],
            [
                'consultation_id' => $consultation_id,
                'rating' => $request['rating'],
                'comment' => $request['comment'] ?? null,
            ]);
            $doctor =Doctor::findOrFail($consultation->doctor_id);
            $data=array(
                'message'=>'A Review has been added to your consultation #'.$consultation_id,
                'consultation_id'=>$consultation_id,
                'doctor'=>$doctor
            );
            $doctor->notify((new DoctorReviewAdded($data)));
            return true;
        } else {
           return response()->json([
            'success'=>false,
            'message'=>'The consultation is not completed yet'
           ]);
        }
        return true;
    }
    /**
     * create a prescription for a given consultation
     */
    public function addPrescription($data,$id)
    {
        $consultation = Consultation::incompleted()->findOrFail($id);
        $consultation->prescription_valid_until = $data['valid_until'];
        $consultation->save();

        if(isset($data['drugs_ids']) && count($data['drugs_ids']))
        {
            $consultation->drugs()->sync($data['drugs_ids']);

        }
        if(isset($data['drugs_data']))
        {
            foreach ($data['drugs_data'] as $drug) {
                $DrugE = Drug::create($drug);
                $consultation->drugs()->save($DrugE);
            }
        }
        return true;
    }
    public function getPrescriptionDetails($consultation_id)
    {
        return Consultation::with('drugs')->with('patient:id,full_name')->findOrFail($consultation_id);
    }

}
