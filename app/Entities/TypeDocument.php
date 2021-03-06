<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeDocument extends Model
{
    /**
     * Relation between type documents and clients
     * @return HasMany
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Relation between type documents and users
     * @return HasMany
     */
    public function sellers(): HasMany
    {
        return $this->hasMany(Seller::class);
    }
}
