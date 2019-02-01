<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Component
 * @property int id
 */
class Component extends Model
{
    const PRIMARY_MAIN_COURSE = 'primary_main_course';
    const ADDON_MAIN_COURSE = 'addon_main_course';
    const SALAD_ADDON_MAIN_COURSE = 'salad_addon_main_course';
    const PRIMARY_FIRST_COURSE = 'primary_first_course';
    const ADDON_FIRST_COURSE = 'addon_first_course';
    const OTHER = 'other';

    const ALLOWED_TYPES = [
        self::PRIMARY_MAIN_COURSE,
        self::ADDON_MAIN_COURSE,
        self::SALAD_ADDON_MAIN_COURSE,
        self::PRIMARY_FIRST_COURSE,
        self::ADDON_FIRST_COURSE,
        self::OTHER
    ];

    protected $fillable = ['name', 'type', 'dinners'];

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dinners()
    {
        return $this->belongsToMany(Dinner::class, 'component_dinner');
    }
}
