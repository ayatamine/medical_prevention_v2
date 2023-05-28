<?php

namespace App\Repositories;

use App\Models\Consultation;
use Exception;
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

}