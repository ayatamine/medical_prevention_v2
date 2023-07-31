<?php

namespace App\Repositories;

use Exception;
use App\Models\Patient;
use App\Models\PatientScale;
use App\Models\ScaleQuestion;
use App\Models\Recommendation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PatientScaleResource;
use App\Models\Scale;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class PatientRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Patient::class;
    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'created_at',
    ];
    /**
     * @return Patient instance
     *  store new Patient record
     */
    public function store($request)
    {
        try {
            // $to_unset = ['thumbnail', 'phone_number'];
            // foreach ($to_unset as $attr) {
            //     if (isset($request[$attr])) {
            //         unset($request[$attr]);
            //     }
            // }
            $request['thumbnail'] =  "https://ui-avatars.com/api/?name=".$request["full_name"]."&background=0D8ABC&color=fff";

            $patient = request()->user()->update($request->except(['phone_number','allergies','chronic_diseases','family_histories']));


            
            if (request()->has('allergies')) {
                if (gettype($request['allergies']) == 'string') {
                    request()->user()->allergies()->sync(explode(',', $request['allergies']));
                } else {
                    request()->user()->allergies()->sync($request['allergies']);
                }
            }
            if (request()->has('chronic_diseases')) {
                if (gettype($request['chronic_diseases']) == 'string') {
                    request()->user()->chronic_diseases()->sync(explode(',', $request['chronic_diseases']));
                } else {
                    request()->user()->chronic_diseases()->sync($request['chronic_diseases']);
                }
            }
            if (request()->has('family_histories')) {
                if (gettype($request['family_histories']) == 'string') {
                    request()->user()->family_histories()->sync(explode(',', $request['family_histories']));
                } else {
                    request()->user()->family_histories()->sync($request['family_histories']);
                }
            }
            return request()->user();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    /**
     * @return boolean
     * check Patient apply request status
     */
    public function findById($id)
    {
        return $this->model::whereDeletedAt(null)->findOrFail($id);
    }
    /**
     * find by otp an and phone
     * 
     * @return collection
     */
    public function findByOtpAndPhone($phone_number, $otp)
    {
        $patient = $this->model::whereDeletedAt(null)->where('phone_number', $phone_number)
            ->firstOrFail();
        if($patient && $patient->otp_verification_code != $otp){
            abort(422,"Your OTP is not correct, Please Verify");  
        }
        return $patient;

        //TODO: verify the otp
        // ->where('otp_verification_code', $otp)
    }
    /**
     * switch on/off notifications
     * 
     * @return boolean
     */
    public function switchNotification($status)
    {
        return request()->user()->update(['notification_status' => $status]);
    }
    /**
     * switch on/off online staus
     * 
     * @return boolean
     */
    public function switchOnlineStatus($status)
    {
        return request()->user()->update(['online_status' => $status]);
    }
    /**
     * update Patient thumbnail
     * 
     * @return Patient instance
     */
    public function updateThumbnail($request)
    {
        $patient = request()->user();
        tap($patient->thumbnail, function ($previous) use ($request, $patient) {
            $patient->update([
                'thumbnail' => $request['thumbnail']->storePublicly(
                    'patients/thumbnails',
                    ['disk' => 'public']
                ),
            ]);

            if ($previous) {
                Storage::disk('public')->delete($previous);
            }
        });

        return $patient;
    }
    /**
     * update Patient phone number
     * 
     * @return Patient instance
     */
    public function updatePhone($request)
    {
        $patient =  request()->user();
        $patient->update(['phone_number' => $request['phone_number']]);

        return $patient;
    }
    /**
     * @return Patient instance
     *  update Patient record
     */
    public function updateProfile($request)
    {
        $Patient = request()->user();

        try {
            //start transaction
            DB::beginTransaction();

            if (isset($request['medical_licence_file'])) {
                if (file_exists(public_path('storage/' . $Patient['medical_licence_file']))) {
                    unlink(public_path('storage/' . $Patient['medical_licence_file']));
                };
                $request['medical_licence_file'] = $request['medical_licence_file']->storePublicly(
                    "Patients/medical_licences",
                    ['disk' => 'public']
                );
            }
            if (isset($request['cv_file'])) {
                if (file_exists(public_path('storage/' . $Patient['cv_file']))) {
                    unlink(public_path('storage/' . $Patient['cv_file']));
                };
                $request['cv_file'] = $request['cv_file']->storePublicly(
                    "Patients/cv_files",
                    ['disk' => 'public']
                );
            }
            if (isset($request['certification_file'])) {
                if (file_exists(public_path('storage/' . $Patient['certification_file']))) {
                    unlink(public_path('storage/' . $Patient['certification_file']));
                };
                $request['certification_file'] = $request['certification_file']->storePublicly(
                    "Patients/certifications",
                    ['disk' => 'public']
                );
            }

            // update speciality
            if (isset($request['sub_specialities'])) {
                $Patient->sub_specialities()->sync(explode(',', $request['sub_specialities']));
            }
            //commit 
            DB::commit();
            return $Patient;
        } catch (Exception $ex) {

            // delete the medical_licence file
            if (isset($request['medical_licence_file'])) {
                $medical_licence = public_path('storage/' . $request['medical_licence_file']);

                if (file_exists($medical_licence)) {
                    unlink($medical_licence);
                };
            }
            // delete the cv file
            if (isset($request['certification_file'])) {
                $certification = public_path('storage/' . $request['certification_file']);

                if (file_exists($certification)) {
                    unlink($certification);
                };
            }
            // delete the medical_licence file
            if (isset($request['cv_file'])) {
                $cv_file = public_path('storage/' . $request['cv_file']);

                if (file_exists($cv_file)) {
                    unlink($cv_file);
                };
            }
            //rollback 
            DB::rollBack();

            throw $ex;
        }
    }
    /** get patient scales */
    public function scalesList()
    {
        $scales = PatientScale::wherePatientId(request()->user()->id)
            ->with(['scaleQuestion' => function ($q) {
                $q->select(['id', 'question', 'question_ar']);
            }])
            ->get()
            ->map(function ($q) {
                return new PatientScaleResource($q);
            });
        if (!count($scales)) {
            foreach (ScaleQuestion::all() as $sq) {
                PatientScale::create([
                    'patient_id' => request()->user()->id,
                    'scale_question_id' => $sq->id,
                ]);
            }
            $this->scalesList();
        }


        return $scales;
    }
    /** get patient scales */
    public function scaleDetails($id)
    {
        $scales = PatientScale::wherePatientId(request()->user()->id)
            ->with(['scaleQuestion' => function ($q)use ($id) {
                $q->select(['id', 'question', 'question_ar','scale_id']);
            }])
            ->whereHas('scaleQuestion',function ($q) use ($id){
                $q->whereScaleId($id);
            })
            ->get()
            ->map(function ($q) {
                return new PatientScaleResource($q);
            });
        if (!count($scales)) {
            foreach (ScaleQuestion::all() as $sq) {
                PatientScale::create([
                    'patient_id' => request()->user()->id,
                    'scale_question_id' => $sq->id,
                ]);
            }
            $this->scalesList();
        }


        return $scales;
    }
    /** get patient recommendations */
    public function recommendationsList()
    {
        $recommendations = Recommendation::publishable()->byAgeAndSex(request()->user()->gender, request()->user()->age ?? 18);

        return $recommendations;
    }
    /** get patient recommendation details */
    public function recommendationDetails($id)
    {
        $recommendation = Recommendation::findOrFail($id);
        return $recommendation;
    }
    public function updateScale($request,$title){
        // $scale = Scale::where('title',$title)->firstOrFail();
        // $patientScale = PatientScale::wherePatientId(request()->user()->id)->get();
        foreach ($responses=$request->answers as $response) {

            $patientResponse = PatientScale::where('patient_id',$response['patient_id'])
                                            ->where('scale_question_id',$response['scale_question_id'])
                                            ->firstOrFail();
            $patientResponse->update($response);
        }
        return true;
    }
}
