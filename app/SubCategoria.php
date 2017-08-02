<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategoria extends Model
{
	protected $table ='subcategoria';
	protected $primaryKey = 'idSubCategoria';
	
	protected $fillable = ['Categoria_idCategoria','codigoSubCategoria', 'nombreSubCategoria'];

	public $timestamps = false;

	public function categoria()
	{
		return $this->hasOne('App\Categoria','idCategoria');
	}
}

	