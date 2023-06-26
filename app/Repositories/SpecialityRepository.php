<?php

namespace App\Repositories;

use Exception;
use App\Models\Doctor;
use App\Models\Speciality;
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
    /**
     * @return speciality doctors
     *  
     */
    public function getDoctors($speciality_id)
    {
    //    return Speciality::select('id','name','name_ar')->with('sub_specialities:id,speciality_id,name')->findOrFail($id);
       $doctors = Doctor::whereHas('sub_specialities.speciality', function ($query) use ($speciality_id) {
        $query->where('id', $speciality_id);
                })->get();
        return $doctors;
    }
}