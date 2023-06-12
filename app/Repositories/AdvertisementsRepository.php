<?php

namespace App\Repositories;

use App\Models\Advertisement;
use Exception;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class AdvertisementsRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Advertisement::class;
    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'created_at',
    ];
    /**
     * @return Advertisement instances 
     *  
     */
    public function fetchAll()
    {
       return Advertisement::publishable()->get();
    }
    /**
     * @return Advertisement instance
     *  
     */
    // public function getDetails($id)
    // {
    //    return Advertisement::select('id','name')->findOrFail($id);
    // }
}