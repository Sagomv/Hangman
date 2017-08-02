<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
	protected $table ='categoria';
	protected $primaryKey = 'idCategoria';
	
	protected $fillable = ['codigoCategoria', 'nombreCategoria'];

	public $timestamps = false;

	public function subcategoria() 
	{
		return $this->hasMany('App\SubCategoria','Categoria_idCategoria');
	}
}
	