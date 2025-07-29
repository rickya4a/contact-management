<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;

class Contact extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    /**
     * Scope a query to search contacts.
     */
    public function scopeSearch(Builder $query, ?string $search = null): Builder
    {
        if (empty($search)) {
            return $query;
        }

        $search = strtolower(trim($search));

        return $query->where(function ($query) use ($search) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(phone) LIKE ?', ["%{$search}%"]);
        });
    }
}
