<?php

namespace App\Repositories;

use Exception;
use App\Models\Doctor;
use App\Models\Rating;
use App\Models\Patient;
use App\Models\Summary;
use App\Models\Consultation;
use App\Models\PatientScale;
use App\Models\ScaleQuestion;
use Illuminate\Support\Facades\DB;
use App\Notifications\DoctorReviewAdded;
use App\Http\Resources\PatientScaleResource;
use App\Notifications\ConsultationStatusUpdated;
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
            'patient'=>$patient
        );

        $delay = now()->addMinutes(5);
        $patient->notify(((new ConsultationStatusUpdated($data))->delay($delay))->delay($delay));
        
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
            'patient'=>$patient
        );
        $delay = now()->addMinutes(5);
        $patient->notify((new ConsultationStatusUpdated($data))->delay($delay));
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
            'patient'=>$patient
        );
        $delay = now()->addMinutes(5);
        $patient->notify((new ConsultationStatusUpdated($data))->delay($delay));
       
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
        $consultation = Consultation::findOrFail($consultation_id);
        $summary = Summary::where('consultation_id',$consultation_id)->first();
        if($summary) return 'exists';
        if ($consultation->status == 'incompleted') { //summay not added yet
            DB::transaction(function () use ($consultation,$consultation_id,$request) {
                $summary = Summary::create([
                    'description' => $request['description'],
                    'sick_leave' => $request['sick_leave'],
                    'other_lab_tests' => $request['other_lab_tests'] ? json_encode($request['other_lab_tests']) :null,
                    'notes' => $request['note'] ?? null,
                    'prescriptions' =>$request['prescriptions'] ? json_encode($request['prescriptions']) :null,
                    'consultation_id' => $consultation_id
                ]);
                if(array_key_exists('lab_tests',$request))
                {
                    if(is_array($request['lab_tests']))
                    {
                     $summary->labTests->sync($request['lab_tests']);
                    }
                }
                //mark as completed
                $consultation->update(['status' => 'completed','finished_at'=>now()]);
                //notify patient
                $patient =Patient::findOrFail($consultation->patient_id);
                    $data=array(
                        'message'=>'your consultation #'.$consultation_id.' with the doctor '.request()->user()->full_name.' has been updated with the summary',
                        'consultation_id'=>$consultation_id,
                        'patient'=>$patient
                    );
                    $delay = now()->addMinutes(5);
                $patient->notify((new ConsultationStatusUpdated($data))->delay($delay));
                return $summary;
            });
        }
        return false;
    }
    /** view a summary */
    public function viewSummary($consultation_id)
    {
        return Consultation::with('summary','review')->findOrFail($consultation_id);
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
            $delay = now()->addMinutes(5);
            $doctor->notify((new DoctorReviewAdded($data))->delay($delay));
            return true;
        } else {
           return response()->json([
            'success'=>false,
            'message'=>'The consultation is not completed yet'
           ]);
        }
        return true;
    }
}
