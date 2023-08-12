<?php

namespace App\Repositories;

use App\Models\LabTest;
use Exception;
use App\Models\Medicine;
use App\Models\Prescription;
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
    public function searchMedicineList(){
        $page = request()->has('page') ? request()->get('page') : 1;
        $limit = request()->has('limit') ? request()->get('limit') : 30;

        $medicines = Medicine::where('commercial_name','like','%'.request()->query()['search'].'%')
                     ->orWhere('scientific_name','like','%'.request()->query()['search'].'%')
                     ->limit($limit)
                     ->offset(($page - 1) * $limit)
                     ->get();
        return $medicines;
    }
    public function labTests()
    {
        return LabTest::select('id','name','name_ar')
                    ->when(array_key_exists('limit',request()->query()),function($query){
                        $query->paginate(request()->query()['limit']);
                    })
                    ->get();

    }

}