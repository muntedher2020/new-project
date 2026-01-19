<?php

namespace App\Models\Backend\ElectronicForms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Backend\ElectronicForms\ElectronicForms;
use App\Models\User;

class FormResponses extends Model
{
    protected $guarded = [];
    protected $table = "form_responses";

    protected $casts = [
        'response_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(ElectronicForms::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // التحقق من إمكانية التعديل
    public function canBeModified(): bool
    {
        return $this->status === 'pending' || $this->status === 'under_review';
    }

    // تغيير حالة الإجابة
    public function changeStatus($newStatus, $notes = null)
    {
        $this->status = $newStatus;
        if ($notes) {
            $this->notes = $notes;
        }
        return $this->save();
    }

    public function getFieldValue($fieldName)
    {
        return $this->response_data[$fieldName] ?? null;
    }

    public function getStatusBadge(): string
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'under_review' => 'info',
        ];

        $status = $this->status;
        $badgeClass = $badges[$status] ?? 'secondary';

        return '<span class="badge bg-' . $badgeClass . '">' . trans('form.status.' . $status) . '</span>';
    }
}
