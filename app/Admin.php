<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Permission;

class Admin extends Authenticatable
{
    //
    protected $guard = 'subadmin';
    protected $table = 'admin';


    public function user_permissions()
    {
        return $this->belongsToMany(Permission::class,'user_permission');
    }

    public function test(){
        return "hello";
    }
    public function hasPermission(string $permission,$id)
    {
        //echo $permission; die;
       $result = \DB::table('permission_user')
            ->join('permissions','permission_user.permission_id','=','permissions.id')
            ->where('permissions.permission_title',$permission)
            ->where('permission_user.admin_id',$id)
            ->first();
       //dd($result);
        if(isset($result) && !empty($result))
        {
            return true;
        }
        else
        {
            return false;
        }

    }
}
