<?php

namespace App\Repositories;

use App\Models\Allergy;
use Exception;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class AllergyRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Allergy::class;
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
        // if(array_key_exists('limit',request()->query()))  return $this->model::select('id','name','name_ar','icon')->limit(request()->query()['limit'])->get();
        return $this->model::select('id','name','name_ar','icon')
                    ->when(array_key_exists('limit',request()->query()),function($query){
                        $query->paginate(request()->query()['limit']);
                    })
                    ->get();
    }

}