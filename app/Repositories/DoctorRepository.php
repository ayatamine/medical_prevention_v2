<?php

namespace App\Repositories;

use App\Models\Doctor;
use Exception;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class DoctorRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Doctor::class;
    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'created_at',
    ];
    /**
     * @return Doctor instance
     *  store new doctor record
     */
    public function store($request){
        try{
            $request['medical_licence_file'] = $request['medical_licence_file']->storePublicly(
                "doctors/medical_licences", ['disk' => 'public']
            );
            $request['cv_file'] = $request['cv_file']->storePublicly(
                "doctors/cv_files", ['disk' => 'public']
            );
            $request['certification_file'] = $request['certification_file']->storePublicly(
                "doctors/certifications", ['disk' => 'public']
            );
            $doctor = Doctor::create($request);
            
            // update speciality
            if(isset($request['sub_specialities'])){
                $doctor->sub_specialities()->sync(explode(',',$request['sub_specialities']));
            }

            return $doctor;
        }
        catch(Exception $ex){
        
            // delete the medical_licence file
            $medical_licence = public_path('storage/'.$request['medical_licence_file']);
            
            if(file_exists($medical_licence)){
                unlink($medical_licence);
            };
            // delete the cv file
            $certification = public_path('storage/'.$request['certification_file']);
            
            if(file_exists($certification)){
                unlink($certification);
            };
            // delete the medical_licence file
            $cv_file = public_path('storage/'.$request['cv_file']);
            
            if(file_exists($cv_file)){
                unlink($cv_file);
            };
            throw $ex;
        }

    }
     /**
      * @return boolean
     * check doctor apply request status
     */
    public function findById($id){
        return Doctor::findOrFail($id);
    }
    
}