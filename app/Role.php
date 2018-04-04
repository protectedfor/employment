<?php namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

    public function setPermsAttribute($perms)
    {
        $this->perms()->detach();
        if ( ! $perms) return;
        if ( ! $this->exists) $this->save();

        $this->perms()->attach($perms);
    }
}