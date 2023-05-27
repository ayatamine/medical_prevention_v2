<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\BallanceHistory;
use Illuminate\Http\Resources\Json\JsonResource;

class BallanceHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $attr =  [
            'amount' => $this->amount,
            'operation_name'=>$this->operation_name,
            'date'=>$this->created_at,
        ];
        if($this->operation_type == BallanceHistory::$RFC || $this->operation_type == BallanceHistory::$PFC){
            //TODO: implement consult id
            $attr['operation_name'] = $this->operation_name.' #1656';
            $attr['consult_id'] =1656;
            $attr['patient_name'] =request()->user()->full_name;
        }
        return $attr;
    }
}
