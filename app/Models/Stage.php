<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'success_message',
        'fail_message',
        'link',
        'current_stage',
    ];

    /**
     * Get the caas stages for the stage.
     */
    public function caasStages()
    {
        return $this->hasMany(CaasStage::class);
    }

    /**
     * Get the configurations using this stage.
     */
    public function configurations()
    {
        return $this->hasMany(Configuration::class, 'stage_id');
    }
}
