<?php

namespace App\Repositories;

use App\Models\ChronicDiseases;
use Exception;
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
        if(array_key_exists('limit',request()->query()))return ChronicDiseases::select('id','name','name_ar','icon')->limit(request()->query()['limit'])->get();
        return ChronicDiseases::select('id','name','name_ar','icon')->get();
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