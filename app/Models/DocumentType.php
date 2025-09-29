<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $table = 'type_documents';
    protected $fillable = ['short_name', 'name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
