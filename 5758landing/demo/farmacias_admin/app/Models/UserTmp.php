<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class UserTmp extends Eloquent {
    public $timestamps = false;
    protected $table = 'usersTemp';
    protected $primaryKey = 'id';

}
