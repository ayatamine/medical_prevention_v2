<?php

namespace App\Repositories;

use App\Models\Speciality;
use Exception;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class SpecialityRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Speciality::class;
    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'created_at',
    ];
    /**
     * @return speciality instance with subs
     *  
     */
    public function getWithSubs()
    {
       return Speciality::select('id','name','name_ar')->with('sub_specialities:id,speciality_id,name')->get();
    }
    /**
     * @return speciality instance with subs
     *  
     */
    public function getDetails($id)
    {
       return Speciality::select('id','name','name_ar')->with('sub_specialities:id,speciality_id,name')->findOrFail($id);
    }
}