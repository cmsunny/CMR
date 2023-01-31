<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employee extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table='employee';
    protected $fillable = [
        'fname',
        'lname',
        'company_id',
        'email',
        'phone'
    ];
    protected $dates = [ 'deleted_at' ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
