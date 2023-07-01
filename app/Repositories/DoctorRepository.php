<?php

namespace App\Repositories;

use Exception;
use App\Models\Doctor;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        return Doctor::whereDeletedAt(null)->findOrFail($id);
    }
     /**
     * find by otp an and phone
     * 
     * @return collection
     */
    public function findByOtpAndPhone($phone_number,$otp){
        return Doctor::whereDeletedAt(null)->where('phone_number', $phone_number)
                                            ->firstOrFail();
                                            //TODO: verify the otp
                                            // ->where('otp_verification_code', $otp)
    }
     /**
     * switch on/off notifications
     * 
     * @return boolean
     */
    public function switchNotification($status){
        return request()->user()->update(['notification_status'=>$status]);
    }
     /**
     * switch on/off online staus
     * 
     * @return boolean
     */
    public function switchOnlineStatus($status){
        return request()->user()->update(['online_status'=>$status]);
    }
     /**
     * update doctor phone number
     * 
     * @return doctor instance
     */
    public function updatePhone($request){
        $doctor =  request()->user();
        $doctor->update(['phone_number'=>$request->phone_number]);

        return $doctor;
    }
    /**
     * update Doctor phone number
     * 
     * @return Doctor instance
     */
    public function updateThumbnail($request){
        $doctor = request()->user();
        tap($doctor->thumbnail, function ($previous) use ($request, $doctor) {
            $doctor->update([
                'thumbnail' => $request['thumbnail']->storePublicly(
                    'doctors/thumbnails',
                    ['disk' => 'public']
                ),
            ]);

            if ($previous) {
                Storage::disk('public')->delete($previous);
            }
        });

        return $doctor;
    }
       /**
     * @return Doctor instance
     *  update doctor record
     */
    public function updateProfile($request){
        $doctor = request()->user();
            
        try{
            //start transaction
            DB::beginTransaction();

            if(isset($request['medical_licence_file'])){
                if(file_exists(public_path('storage/'.$doctor['medical_licence_file']))){
                    unlink(public_path('storage/'.$doctor['medical_licence_file']));
                };
                $request['medical_licence_file'] = $request['medical_licence_file']->storePublicly(
                    "doctors/medical_licences", ['disk' => 'public']
                );
            }
            if(isset($request['cv_file'])){
                if(file_exists(public_path('storage/'.$doctor['cv_file']))){
                    unlink(public_path('storage/'.$doctor['cv_file']));
                };
                $request['cv_file'] = $request['cv_file']->storePublicly(
                    "doctors/cv_files", ['disk' => 'public']
                );
            }
            if(isset($request['certification_file'])){
                if(file_exists(public_path('storage/'.$doctor['certification_file']))){
                    unlink(public_path('storage/'.$doctor['certification_file']));
                };
                $request['certification_file'] = $request['certification_file']->storePublicly(
                    "doctors/certifications", ['disk' => 'public']
                );
            }
            
            // update speciality
            if(isset($request['sub_specialities'])){
                $doctor->sub_specialities()->sync(explode(',',$request['sub_specialities']));
            }
            //commit 
            DB::commit();
            return $doctor;
        }
        catch(Exception $ex){
        
            // delete the medical_licence file
            if(isset($request['medical_licence_file'])){
                $medical_licence = public_path('storage/'.$request['medical_licence_file']);
                
                if(file_exists($medical_licence)){
                    unlink($medical_licence);
                };
            }
            // delete the cv file
            if(isset($request['certification_file'])){
                $certification = public_path('storage/'.$request['certification_file']);
                
                if(file_exists($certification)){
                    unlink($certification);
                };
            }
            // delete the medical_licence file
            if(isset($request['cv_file'])){
                $cv_file = public_path('storage/'.$request['cv_file']);
                
                if(file_exists($cv_file)){
                    unlink($cv_file);
                };
            }
            //rollback 
            DB::rollBack();

            throw $ex;
        }
      
    }
    public function index(){
        $doctors = Doctor::when(Rating::count() > 10 ,function ($query){
                $query->whereHas('reviews', function ($query) {
                    $query->where('rating', '>', 3);
                });
         })
        ->active()
        ->online()
        ->withCount('reviews')
        ->withSum('reviews', 'rating')
        ->when(array_key_exists('limit',request()->query()),function ($query){
            $query->limit(request()->query()['limit']);
        })
        ->get();
        return $doctors;
    }
    /** add doctor to favorites */
    public function addToFavorites($id){
        if(Doctor::findOrFail($id) && !request()->user()->favorites()->where('doctor_id', $id)->exists()) request()->user()->favorites()->attach($id);
    }
    /** add doctor to favorites */
    public function removeFromFavorites($id){
        if(Doctor::findOrFail($id) && request()->user()->favorites()->where('doctor_id', $id)->exists())  request()->user()->favorites()->detach($id);
    }
}