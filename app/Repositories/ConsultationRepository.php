<?php

namespace App\Repositories;

use Exception;
use App\Models\Consultation;
use App\Models\PatientScale;
use App\Models\ScaleQuestion;
use App\Http\Resources\PatientScaleResource;
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
    public function approveConuslt($consultation_id){
        $consultation = request()->user()->consultations()->where('status', 'pending')->findOrFail($consultation_id);
        //TODO: send notification to patient
        $consultation->update(['status' => 'in_progress']);
    }
    /**
     * reject a consultation request
     *  
     */
    public function rejectConsult($consultation_id){
        $consultation =  request()->user()->consultations()->where('status', 'pending')->findOrFail($consultation_id);
         //TODO: send notification to patient
        $consultation->update(['status' => 'rejected']);
    }
    /**
     * add a summary to a consultation
     *  
     */
    public function addSummary($request,$consultation_id){
        //TODO: implement it
        // $consultation =  request()->user()->consultations()->where('status', 'pending')->findOrFail($consultation_id);
        //  //TODO: send notification to patient
        // $consultation->update(['status' => 'rejected']);
    }
    /**
     * fetch patient medical record
     */
    public function patientMedicalRecord($consultation_id)
    {
        $consult = Consultation::with('patient','patient.allergy',"patient.familyHistory","patient.chronicDisease")
                        ->findOrFail($consultation_id);
        $anexiety_scale = $this->patientScales($consult->patient->id,1);
        $depression_scale = $this->patientScales($consult->patient->id,2);
        return array('consult'=>$consult,'anexiety_scale'=>$anexiety_scale,'depression_scale'=>$depression_scale);
    }
    public function patientScales($id,$scale_id){
        $scales = PatientScale::wherePatientId($id)
        ->with(['scaleQuestion' => function ($q) {
            $q->select(['id', 'question', 'question_ar','scale_id']);
        }])
        ->whereHas('scaleQuestion',function ($q) use ($scale_id){
            $q->whereScaleId($scale_id);
        })
        ->get()
        ->map(function ($q) {
            return new PatientScaleResource($q);
        });

    return $scales;
    }


}