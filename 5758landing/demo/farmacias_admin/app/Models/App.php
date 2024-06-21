<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class App extends Eloquent {
    public $timestamps = false;
    protected $table = 'apps';
    protected $primaryKey = 'id';

    public function usersApp() {
        return $this->hasMany(UserApp::class, 'appId', 'id');
    }

    public function rolesAsig() {
        return $this->hasMany(RolApp::class, 'appId', 'id');
    }

    /*public function access() {
        return $this->hasMany(RolAccess::class, 'rolId', 'id');
    }*/

    /*public function detalle() {
        return $this->hasMany(ReglaDetalle::class, 'ruleId', 'id');
    }

    public function modificadorCampo() {
        return $this->belongsTo(CatalogoCampos::class, 'fieldCatalogIdModifierField', 'id');
    }*/
}
