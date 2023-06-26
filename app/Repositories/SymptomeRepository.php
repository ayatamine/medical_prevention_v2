<?php

namespace App\Repositories;

use App\Models\Symptome;
use Exception;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class SymptomeRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Symptome::class;
    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'created_at',
    ];
    /**
     * @return $this->model instances 
     *  
     */
    public function fetchAll()
    {
        if(array_key_exists('limit',request()->query()))  return $this->model::select('id','name','name_ar')->limit(request()->query()['limit'])->get();
        return $this->model::select('id','name','name_ar')->get();
    }
    /**
     * @return $this->model instance 
     *  
     */
    // public function getDetails($id)
    // {
    //    return ChronicDiseases::select('id','name')->findOrFail($id);
    // }
}