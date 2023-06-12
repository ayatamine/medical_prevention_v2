<?php

namespace App\Repositories;

use App\Models\FamilyHistory;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class FamilyHistoryRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = FamilyHistory::class;
    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'created_at',
    ];
    /**
     * @return FamilyHistory instance with subs
     *  
     */
    public function fetchAll()
    {
       return FamilyHistory::select('id','name','name_ar')->get();
    }
    /**
     * @return FamilyHistory instance with subs
     *  
     */
    // public function getDetails($id)
    // {
    //    return FamilyHistory::select('id','name')->findOrFail($id);
    // }
}