<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mockery\Generator\StringManipulationGenerator;

class Profile extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $timestamps = true;
    protected $table = 'profile';
    protected $guarded = ['id'];
    protected $casts = [];

    public function __construct(array $attributes = [])
    {
        $this->casts = [
            'id' => 'int',
            'name' => 'string',
            'age' => 'int',
            'biography' => 'string',
            'image_url' => 'string',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s'
        ];

        parent::__construct($attributes);
    }
}
