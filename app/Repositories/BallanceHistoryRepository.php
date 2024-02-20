<?php

namespace App\Repositories;

use App\Models\Doctor;
use Exception;
use Torann\LaravelRepository\Repositories\AbstractRepository;


class BallanceHistoryRepository extends AbstractRepository
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
     * @return HistoryBallance
     *
     */
   public function ballance_history()
   {

        $ballance_histories = request()->user()->balanceHistories()
                              ->when(request()->period && request()->period != 'all',function($query){
                                switch(request()->period){
                                    case 'last_month' :
                                        return  $query->where('created_at','>',now()->startOfMonth());
                                    case 'last_three_months':
                                        return  $query->where('created_at','>',now()->startOfMonth()->subMonths(3));
                                    case 'last_year':
                                        return  $query->where('created_at','>',now()->startOfYear());
                                    default :
                                        return  $query;
                                }
                              })
                              ->paginate(10);
        return $ballance_histories;
   }
}