<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Medicine extends Model
{
    use HasFactory;

    protected $table = 'medicines';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'category_id', 'medicine_code', 'name', 'unit', 'image', 'description', 'is_active'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = str_replace('-','',Uuid::uuid4()->getHex());
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
