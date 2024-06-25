<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Contact extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'street',
        'city',
        'state',
        'zip',
        'country',
        'vat',
        'type',
        'parent_id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->BelongsTo(Contact::class, 'parent_id', 'id');
    }

    public function childs(): HasMany
    {
        return $this->HasMany(Contact::class, 'parent_id', 'id');
    }
}