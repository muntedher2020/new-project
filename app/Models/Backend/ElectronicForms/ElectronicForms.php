<?php

namespace App\Models\Backend\ElectronicForms;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\ElectronicForms\FormFields;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Backend\ElectronicForms\FormResponses;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectronicForms extends Model
{
    protected $guarded = [];
    protected $table = "electronic_forms";

    protected $casts = [
        'form_fields' => 'array',
        'active' => 'boolean',
        'require_login' => 'boolean',
        'allow_multiple' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'settings' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormFields::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(FormResponses::class);
    }

    public function isOpen(): bool
    {
        if (!$this->active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        if ($this->max_responses && $this->responses()->count() >= $this->max_responses) {
            return false;
        }

        return true;
    }

    public function getFieldsArray(): array
    {
        if ($this->form_fields) {
            return $this->form_fields;
        }

        return $this->fields->map(function ($field) {
            return [
                'id' => $field->id,
                'label' => $field->label,
                'name' => $field->name,
                'type' => $field->type,
                'required' => $field->required,
                'placeholder' => $field->placeholder,
                'options' => $field->options,
                'validation_rules' => $field->validation_rules,
                'settings' => $field->settings,
            ];
        })->toArray();
    }

    public function generateSlug(): string
    {
        return Str::slug($this->title) . '-' . Str::random(6);
    }
}
