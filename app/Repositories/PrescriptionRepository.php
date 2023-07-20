<?php

namespace App\Repositories;

use App\Models\Prescription;
use Exception;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class PrescriptionRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Prescription::class;
    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'created_at',
    ];


    /**
     * create new prescription
     *  
     */
    public function store($request){
        $prescription = new Prescription($request);
        $prescription = request()->user()->prescriptions()->save($prescription);
        return $prescription;
    }
    public function myMedicineList(){
        $medicines = Prescription::where('doctor_id',request()->user()->id)
                     ->when(request()->has('search'),function($query){
                         $query->where('drug_name','like','%'.request()->query()['search'].'%');
                     })
                     ->get();
        return $medicines;
    }

}