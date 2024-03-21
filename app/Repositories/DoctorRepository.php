<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Rating;
use App\Models\DoctorAvailability;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewDoctorRegisteration;
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
    public function store($request)
    {
        try {
            if (array_key_exists('medical_licence_file', $request)) {
                $request['medical_licence_file'] = $request['medical_licence_file']->storePublicly(
                    "doctors/medical_licences",
                    ['disk' => 'public']
                );
            }
            if (array_key_exists('cv_file', $request)) {
                $request['cv_file'] = $request['cv_file']->storePublicly(
                    "doctors/cv_files",
                    ['disk' => 'public']
                );
            }
            // if (array_key_exists('certification_file', $request)) {
            //     $request['certification_file'] = $request['certification_file']->storePublicly(
            //         "doctors/certifications",
            //         ['disk' => 'public']
            //     );
            // }
            if (array_key_exists('thumbnail', $request)) {
                $request['thumbnail'] = $request['thumbnail']->storePublicly(
                    "/",
                    ['disk' => 'public']
                );
            }
            // $doctor = Doctor::where('phone_number',$request['phone_number'])->first();
            $doctor = request()->user();
            // if($doctor){
            $doctor->update($request);
            // }else{
            //     $doctor = Doctor::create($request);
            // }

            // update speciality
            if (array_key_exists('sub_specialities', $request)) {
                if (gettype($request['sub_specialities']) == 'string') {
                    $ids = explode(',', $request['sub_specialities']);
                    $filtered_data= array_filter($ids, function ($value) {
                        return $value;
                    });
                    $doctor->sub_specialities()->sync($filtered_data);
                } else {
                    $ids =array_filter($request['sub_specialities'], function ($value) {
                        return is_int($value);
                    });

                    $doctor->sub_specialities()->sync($ids);
                }
            }
            $delay = now()->addMinutes(5);
            $doctors_count = Doctor::where('account_status','pending')->count();
            if($doctors_count == 5)
            {
             $admins = User::where('is_admin',true)->get();
             Notification::send($admins, (new NewDoctorRegisteration(array('count'=>$doctors_count)))->delay($delay));
            }


            return $doctor;
        } catch (Exception $ex) {

            // delete the medical_licence file
            $medical_licence = public_path('storage/' . $request['medical_licence_file']);

            if (file_exists($medical_licence)) {
                unlink($medical_licence);
            };
            // delete the cv file
            // $certification = public_path('storage/' . $request['certification_file']);

            // if (file_exists($certification)) {
            //     unlink($certification);
            // };
            // delete the medical_licence file
            $cv_file = public_path('storage/' . $request['cv_file']);

            if (file_exists($cv_file)) {
                unlink($cv_file);
            };
            throw $ex;
        }
    }
    /**
     * @return boolean
     * check doctor apply request status
     */
    public function findById($id)
    {
        return Doctor::whereDeletedAt(null)->findOrFail($id);
    }
    /**
     * find by otp an and phone
     *
     * @return collection
     */
    public function findByOtpAndPhone($phone_number, $otp)
    {
        if(request()->user())
        {
           $doctor = request()->user();
        }else
        {
            $doctor = Doctor::whereDeletedAt(null)->where('phone_number', $phone_number)
            ->firstOrFail();
        }
        if($doctor && $doctor->otp_verification_code != $otp){
            abort(422,"Your OTP is not correct, Please Verify");
        }
        return $doctor;

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
     * switch on/off online staus
     *
     * @return boolean
     */
    public function updateLastOnlineStatus()
    {
         request()->user()->update(['last_online_at' => now()]);
    }
    /**
     * update doctor phone number
     *
     * @return doctor instance
     */
    public function updatePhone($request)
    {
        $doctor =  request()->user();
        $doctor->update(['phone_number' => $request->phone_number]);

        return $doctor;
    }
    /**
     * update Doctor phone number
     *
     * @return Doctor instance
     */
    public function updateThumbnail($request)
    {
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
    public function updateProfile($request)
    {
        $doctor = request()->user();

        try {
            //start transaction
            DB::beginTransaction();

            if (array_key_exists('medical_licence_file', $request)) {
                if ($doctor['medical_licence_file'] && file_exists(public_path('storage/' . $doctor['medical_licence_file']))) {
                    unlink(public_path('storage/' . $doctor['medical_licence_file']));
                };
                $request['medical_licence_file'] = $request['medical_licence_file']->storePublicly(
                    "doctors/medical_licences",
                    ['disk' => 'public']
                );
            }
            if (array_key_exists('cv_file', $request)) {
                if ($doctor['cv_file'] && file_exists(public_path('storage/' . $doctor['cv_file']))) {
                    unlink(public_path('storage/' . $doctor['cv_file']));
                };
                $request['cv_file'] = $request['cv_file']->storePublicly(
                    "doctors/cv_files",
                    ['disk' => 'public']
                );
            }
            if (array_key_exists('certification_file', $request)) {
                if ($doctor['certification_file'] && file_exists(public_path('storage/' . $doctor['certification_file']))) {
                    unlink(public_path('storage/' . $doctor['certification_file']));
                };
                $request['certification_file'] = $request['certification_file']->storePublicly(
                    "doctors/certifications",
                    ['disk' => 'public']
                );
            }
            if (array_key_exists('thumbnail', $request)) {
                if ($doctor['thumbnail'] && file_exists(public_path('storage/' . $doctor['thumbnail']))) {
                    unlink(public_path('storage/' . $doctor['thumbnail']));
                };
                $request['thumbnail'] = $request['thumbnail']->storePublicly(
                    "/",
                    ['disk' => 'public']
                );
            }

            // update speciality
            if (array_key_exists('sub_specialities', $request)) {
                if (gettype($request['sub_specialities']) == 'string') {
                    $ids = explode(',', $request['sub_specialities']);
                    $filtered_data= array_filter($ids, function ($value) {
                        return $value;
                    });
                    $doctor->sub_specialities()->sync($filtered_data);
                } else {
                    $ids =$integers = array_filter($request['sub_specialities'], function ($value) {
                        return is_int($value);
                    });

                    $doctor->sub_specialities()->sync($ids);
                }
            }
            // if (array_key_exists('medical_licence_file', $request)) {
            //     unset($request['medical_licence_file']);
            // }
            // if (array_key_exists('cv_file', $request)) {
            //     unset($request['cv_file']);
            // }
            // if (array_key_exists('certification_file', $request)) {
            //     unset($request['certification_file']);
            // }
            // if (array_key_exists('thumbnail', $request)) {
            //     unset($request['thumbnail']);
            // }
            if (array_key_exists('sub_specialities', $request)) {
                unset($request['sub_specialities']);
            }

            $doctor->update($request);

            DB::commit();
            return $doctor;
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
            if (isset($request['thumbnail'])) {
                $thumbnail = public_path('storage/' . $request['thumbnail']);

                if (file_exists($thumbnail)) {
                    unlink($thumbnail);
                };
            }
            //rollback
            DB::rollBack();

            throw $ex;
        }
    }
    public function index()
    {

        $search = request()->query('search'); // Get the search keyword
        $symptomes = request()->query('symptomes'); // Get the search keyword
        $chronic_diseases = request()->query('chronic_diseases'); // Get the search keyword
        // $bestRated = request()->query('best_rated'); // Get the best-rated filter
        // $latest = request()->query('latest'); // Get the lower price filter
        // $oldest = request()->query('oldest'); // Get the lower price filter
        // $is_online = request()->query('is_online'); // Get the lower price filter
        $sort = request()->query('sort');

        $doctors = Doctor::active()->withCount('reviews')
            ->withSum('reviews', 'rating')
            ->when($sort == "best_rated", function ($query) {
                $query->orderBy('reviews_sum_rating', 'desc');
            })
            ->when($sort == "latest", function ($query) {
                $query->latest();
            })
            ->when($sort == "oldest", function ($query) {
                $query->oldest();
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('full_name', 'like', '%' . $search . '%')
                        ->orWhere('job_title', 'like', '%' . $search . '%');
                });
            })
            ->when($symptomes, function ($query, $symptomes) {
                    $query->orWhereHas('speciality.symptomes', function ($query) use ($symptomes) {
                        $query->whereIn('symptome_id', $symptomes);
                    });
            })
            ->when($chronic_diseases, function ($query, $chronic_diseases) {
                    $query->orWhereHas('speciality.chronic_diseases', function ($query) use ($chronic_diseases) {
                        $query->whereIn('chronic_diseases_id', $chronic_diseases);
                    });
            })
            ->when($sort == "is_online", function ($query, $search) {
                $query->online();
            });
        return $doctors;
    }
    /** add doctor to favorites */
    public function addToFavorites($id)
    {
        if (Doctor::findOrFail($id) && !request()->user()->favorites()->where('doctor_id', $id)->exists()) request()->user()->favorites()->attach($id);
    }
    /** add doctor to favorites */
    public function removeFromFavorites($id)
    {
        if (Doctor::findOrFail($id) && request()->user()->favorites()->where('doctor_id', $id)->exists())  request()->user()->favorites()->detach($id);
    }
    /**
     * @return speciality doctors
     *
     */
    public function searchDoctors($speciality_id)
    {
        $search = request()->query('search'); // Get the search keyword
        $bestRated = request()->query('best_rated'); // Get the best-rated filter
        $latest = request()->query('latest'); // Get the lower price filter
        $oldest = request()->query('oldest'); // Get the lower price filter
        $is_online = request()->query('is_online'); // Get the lower price filter

        $doctors = Doctor::active()->withCount('reviews')
            ->withSum('reviews', 'rating')
            ->whereHas('speciality', function ($query) use ($speciality_id) {
                $query->where('id', $speciality_id);
            })
            ->when($bestRated, function ($query) {
                $query->orderBy('reviews_sum_rating', 'desc');
            })
            ->when($latest, function ($query) {
                $query->latest();
            })
            ->when($oldest, function ($query) {
                $query->oldest();
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('full_name', 'like', '%' . $search . '%')
                        ->orWhere('job_title', 'like', '%' . $search . '%');
                });
            })
            ->when($is_online, function ($query, $search) {
                $query->where('online_status', true);
            })->when(array_key_exists('limit', request()->query()), function ($query) {
                $query->paginate(request()->query()['limit']);
            })
            ->get();
        return $doctors;
    }
    public function show($id)
    {
        $doctor =  Doctor::active()
            ->with('reviews','reviews.consultation')
            ->withSum('reviews', 'rating')
            ->with('sub_specialities:id,name,name_ar,slug')
            ->findOrfail($id);
        return $doctor;
    }
    public function searchWithSymptomes($request)
    {

        $search =$request->search;
        $sort =$request->sort;
        $selectedSymptoms = $request->symptomes;
        if(is_array($selectedSymptoms)) {
            $doctors = Doctor::active()->withCount('reviews')
                ->withSum('reviews', 'rating')
                ->whereHas('sub_specialities.symptomes', function ($query) use ($selectedSymptoms) {
                    $query->whereIn('symptomes.id', $selectedSymptoms);
                })
                ->when($sort == "best_rated", function ($query) {
                    $query->orderBy('reviews_sum_rating', 'desc');
                })
                ->when($sort == "latest", function ($query) {
                    $query->latest();
                })
                ->when($sort == "oldest", function ($query) {
                    $query->oldest();
                })
                ->when($search, function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('full_name', 'like', '%' . $search . '%')
                            ->orWhere('job_title', 'like', '%' . $search . '%');
                    });
                })
                ->when($sort == "is_online", function ($query, $search) {
                    $query->online();
                });
        }
        else
        {
            $ids = explode(',',$selectedSymptoms);
            $ids = array_filter($ids,function($item){
                return $item != null;
            });
            $doctors = Doctor::active()->withCount('reviews')
            ->withSum('reviews', 'rating')
            ->whereHas('sub_specialities.symptomes', function ($query) use ($ids) {
                $query->whereIn('symptomes.id',$ids);
            })
            ->when($sort == "best_rated", function ($query) {
                $query->orderBy('reviews_sum_rating', 'desc');
            })
            ->when($sort == "latest", function ($query) {
                $query->latest();
            })
            ->when($sort == "oldest", function ($query) {
                $query->oldest();
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('full_name', 'like', '%' . $search . '%')
                        ->orWhere('job_title', 'like', '%' . $search . '%');
                });
            })
            ->when($sort == "is_online", function ($query, $search) {
                $query->online();
            })
            ->get();
        }
        return $doctors;
    }
    /**
     * update doctor availability
     */
    public function updateCalendar($request)
    {
        // Delete any remaining availabilities that are not included in the update (optional)
        $existingAvailability = DoctorAvailability::where('doctor_id', request()->user()->id)->get();
        foreach ($existingAvailability as $availability) {
            $availability->delete();
        }
        foreach ($request['availabilities'] as $availability) {

            $startTime = $availability['start_time'];
            $endTime = $availability['end_time'];
            $isPm = (bool) $availability['is_pm'];



                // Create new record
                DoctorAvailability::create([
                    'doctor_id' => request()->user()->id,
                    'day_of_week' => $availability['day_of_week'],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_pm' => $isPm,
                ]);


        }


        $calander =  DoctorAvailability::where('doctor_id', request()->user()->id)->oldest('day_of_week')->get()->groupBy('day_of_week')->makeHidden(['doctor_id','created_at','updated_at']);
        $ids  = array_keys($calander->toArray());
        for ($i=1; $i <= 7; $i++) {
            if(!in_array($i,$ids))
            {
                $calander[$i] = 'vacation';
            }
        }
        return $calander->sortKeys();
    }
    public function doctorCalander($id=null)
    {
        $calander =  DoctorAvailability::where('doctor_id',$id !=null ? $id : request()->user()->id)->oldest('day_of_week')->get()->groupBy('day_of_week')->makeHidden(['doctor_id','created_at','updated_at']);
        $ids  = array_keys($calander->toArray());
        for ($i=1; $i <= 7; $i++) {
            if(!in_array($i,$ids))
            {
                $calander[$i] = 'vacation';
            }
        }
        return $calander->sortKeys();
    }
}
