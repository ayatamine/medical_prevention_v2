<?php

namespace App\Http\Resources;

use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $speciality =null;
        // if($this->sub_specialities && count($this->sub_specialities)) {
        //    $speciality = Speciality::whereHas('sub_specialities',function($query){
        //         $query->whereIn('id',array($this->sub_specialities[0]->id));
        //    })->first();
        // }      
        $countryCode = (substr( $this->phone_number, 0, 3) !='+20')? substr( $this->phone_number, 0, 4) :substr( $this->phone_number, 0, 3);
        $phone_number = (substr( $this->phone_number, 0, 3) !='+20')? substr( $this->phone_number, 4) :substr( $this->phone_number, 3) ;
        return [
            "id" => $this->id,
            "full_name" => $this->full_name,
            "id_number" => $this->id_number,
            "birth_date" => $this->birth_date,
            "phone_number" => $phone_number,
            "country_code" => $countryCode,
            "email" => $this->email,
            "job_title" => $this->job_title,
            "thumbnail" => $this->thumbnail ,
            "classification_number" => $this->classification_number,
            "insurance_number" => $this->insurance_number,
            "medical_licence_file" => $this->medical_licence_file,
            "certification_file" => $this->certification_file,
            "cv_file" => $this->cv_file,
            "notification_status" => $this->notification_status,
            "online_status" => $this->online_status,
            "gender" => $this->gender,
            "speciality"=>$this->speciality?->name,
            // "specialities"=>$this->specialities->map(function($sub){
            //     return [
            //         'id'=>$sub->id,
            //         'name'=>$sub->name,
            //         'name_ar'=>$sub->name_ar
            //     ];
            // }),
            'sub_specialities'=> $this->sub_specialities->map(function($sub){
                return [
                    'id'=>$sub->id,
                    'name'=>$sub->name,
                    'name_ar'=>$sub->name_ar
                ];
            }),   
        ];
    }
}
