<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'value', 'reference', 'paid', 'account_id', 'user_id'];

    protected $casts = [
        'reference' => 'date:Y-m-d'
    ];

    public function account() {
        return $this->belongsTo(Account::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function scopeArchived($query) {
        return $query->withTrashed()->onlyTrashed();
    }

    public function scopeLatestFirst($query) {
        return $query->orderByDesc('reference')->orderByDesc('id');
    }
 
}
