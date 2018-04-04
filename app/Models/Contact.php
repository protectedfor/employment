<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $fillable = [
        'address',
        'work_hours',
        'phone',
        'email',
        'url',
        'skype',
        'google_map_code',
        'facebook_url',
        'twitter_url',
        'google_plus_url',
        'instagram_url',
        'diesel_url',
        'vk_url',
        'ok_url'
    ];

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    public function getPhoneAttribute()
    {
        return explode(',', $this->attributes['phone']);
    }

    public function getStringPhoneAttribute()
    {
        return $this->attributes['phone'];
    }

    public function setStringPhoneAttribute($value)
    {
        $this->attributes['phone'] = $value;
    }

}
