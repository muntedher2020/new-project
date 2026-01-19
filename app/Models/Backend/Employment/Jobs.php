<?php

namespace App\Models\Backend\Employment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Backend\ElectronicForms\ElectronicForms;

class Jobs extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $table = "jobs";

    protected $casts = [
        'is_active' => 'boolean',
        'salary_from' => 'decimal:2',
        'salary_to' => 'decimal:2',
        'deadline' => 'date',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(ElectronicForms::class);
    }
}
