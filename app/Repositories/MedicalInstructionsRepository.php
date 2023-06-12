<?php

namespace App\Repositories;

use App\Models\MedicalInstruction;
use Exception;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class MedicalInstructionsRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = MedicalInstruction::class;
    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'created_at',
    ];
      /**
     * @return MedicalInstruction instances
     *  
     */
    public function fetchAll()
    {
       return MedicalInstruction::publishable()->get();
    }
    /**
     * @return MedicalInstruction instance
     *  
     */
    // public function getDetails($id)
    // {
    //    return MedicalInstruction::select('id','name')->findOrFail($id);
    // }
}