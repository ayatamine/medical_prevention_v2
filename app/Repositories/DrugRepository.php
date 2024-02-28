<?php

namespace App\Repositories;

use App\Models\LabTest;
use Exception;
use App\Models\Medicine;
use App\Models\Drug;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class DrugRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Drug::class;
    /**
     * Valid orderable columns.
     *
     * @return array
     */
    protected $orderable = [
        'created_at',
    ];


    /**
     * create new Drug
     *
     */
    public function store($request){
        $Drug = new Drug($request);
        $Drug = request()->user()->drugs()->save($Drug);
        return $Drug;
    }
    public function myMedicineList(){
        $medicines = Drug::where('doctor_id',request()->user()->id)
                     ->when(request()->has('search'),function($query){
                         $query->where('drug_name','like','%'.request()->query()['search'].'%');
                     })->paginate(10);
                    //  ->get()->groupBy('drug_name');
                    //  ->makeHidden('Drug_title');
        return $medicines;
    }
    public function searchMedicineList(){
        $page = request()->has('page') ? request()->get('page') : 1;
        $limit = request()->has('limit') ? request()->get('limit') : 30;

        $medicines = Medicine::where('commercial_name','like','%'.request()->query()['search'].'%')
                     ->orWhere('scientific_name','like','%'.request()->query()['search'].'%')
                     ->limit($limit)
                     ->offset(($page - 1) * $limit)
                     ->get();
        return $medicines;
    }
    public function labTests()
    {
        return LabTest::select('id','name','name_ar')
                    ->when(array_key_exists('limit',request()->query()),function($query){
                        $query->paginate(request()->query()['limit']);
                    })
                    ->get();

    }
    public function destroy($id)
    {
        Drug::findOrFail($id)->delete();
        return true;
    }


}