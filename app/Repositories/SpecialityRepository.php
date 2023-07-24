<?php

namespace App\Repositories;

use Exception;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\SubSpeciality;
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
        return Speciality::select('id', 'name', 'name_ar')->get();
    }
    /**
     * @return speciality instance with subs
     *  
     */
    public function getSubSpecialities()
    {
        return SubSpeciality::select('id', 'name', 'name_ar')->get();
    }
    /**
     * @return speciality instance with subs
     *  
     */
    public function getDetails($id)
    {
        return Speciality::select('id', 'name', 'name_ar','icon')->findOrFail($id);
    }
     /**
     * @return speciality instance with subs
     *  
     */
    public function getSubSpecialityDetails($id)
    {
        return SubSpeciality::select('id', 'name', 'name_ar','icon')->findOrFail($id);
    }
    /**
     * @return speciality doctors
     *  
     */
    public function getDoctors($speciality_id)
    {
        $search = request()->query('search'); // Get the search keyword
        $bestRated = request()->query('best_rated'); // Get the best-rated filter
        $latest = request()->query('latest'); // Get the lower price filter
        $oldest = request()->query('oldest'); // Get the lower price filter
        $is_online = request()->query('is_online'); // Get the lower price filter

        $doctors = Doctor::active()->withCount('reviews')
            ->withSum('reviews', 'rating')
            ->whereHas('specialities', function ($query) use ($speciality_id) {
                $query->where('speciality_id', $speciality_id);
            });
        return $doctors;
    }
    /**
     * @return subspeciality doctors
     *  
     */
    public function SubSpecialityDoctors($sub_speciality_id)
    {
        $search = request()->query('search'); // Get the search keyword
        $bestRated = request()->query('best_rated'); // Get the best-rated filter
        $latest = request()->query('latest'); // Get the lower price filter
        $oldest = request()->query('oldest'); // Get the lower price filter
        $is_online = request()->query('is_online'); // Get the lower price filter

        $doctors = Doctor::active()->withCount('reviews')
            ->withSum('reviews', 'rating')
            ->whereHas('sub_specialities', function ($query) use ($sub_speciality_id) {
                $query->where('sub_speciality_id', $sub_speciality_id);
            });
        return $doctors;
    }
  
}
