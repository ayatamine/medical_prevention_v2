<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportSubjectType extends Model
{
    use HasFactory;
    protected $fillable=['name','name_ar'];
    public function support_requests():HasMany
    {
        return $this->hasMany(SupportRequest::class);
    }
}
