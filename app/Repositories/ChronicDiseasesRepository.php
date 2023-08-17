<?php

namespace App\Repositories;

use Exception;
use App\Models\ChronicDiseases;
use App\Models\ChronicDiseaseCategory;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class ChronicDiseasesRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = ChronicDiseases::class;
    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'created_at',
    ];
    /**
     * @return ChronicDiseases instances 
     *  
     */
    public function fetchAll()
    {
        return  ChronicDiseaseCategory::with('chronicDiseases')
        ->when(array_key_exists('limit',request()->query()),function($query){
            $query->paginate(request()->query()['limit']);
        })
        ->get();
    }
    /**
     * @return ChronicDiseases instance 
     *  
     */
    // public function getDetails($id)
    // {
    //    return ChronicDiseases::select('id','name')->findOrFail($id);
    // }
}