@extends('layouts.vista')
@section('titulo')<h3 id="titulo"><center>Hangman</center></h3>@stop

@section('content')
{!!Html::script('js/hangman.js'); !!}
{!!Form::open(['method'=>'POST'])!!}
<?php session_start(); ?>
<div id='form-section' >
<input type="hidden" id="token" value="{{csrf_token()}}"/>    
	<fieldset id="etiqueta-form-fieldset">	

		<div class="form-group col-md-12" id='test'>
          {!!Form::label('Categoria_idCategoria', 'Categoría', array('class' => 'col-sm-2 control-label')) !!}
          <div class="col-sm-6">
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-tag"></i>
              </span>
              {!!Form::select('Categoria_idCategoria',$categoria, null,["class" => "select form-control","placeholder" =>"Seleccione una categoría", 'onchange' => 'iniciarJuego(this.value)', 'required' => 'required'])!!}
            </div>
          </div>
        </div>

        <div class="form-group col-md-12" id='test'>
          {!!Form::label('letra', 'Letra', array('class' => 'col-sm-2 control-label')) !!}
          <div class="col-sm-2">
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-pencil-square-o"></i>
              </span>
              {!!Form::text('letra',null,['class'=>'form-control','id'=>'letra', 'onkeypress' => "return soloLetras(event)" ,'maxlength' => '1', 'disabled'])!!}
            </div>
          </div>
          {!!Form::button('Jugar',["class"=>"btn btn-primary", 'onclick' => 'jugar($(\'#letra\').val());'])!!}
        </div>

        {!!Form::hidden('palabra', null, array('id' => 'palabra')) !!}
        {!!Form::hidden('cifrado[]', null, array('id' => 'cifrado')) !!}
        {!!Form::hidden('fallos', null, array('id' => 'fallos')) !!}
        {!!Form::hidden('letrajugada', null, array('id' => 'letrajugada')) !!}
        {!!Form::hidden('errores', 0, array('id' => 'errores')) !!}
        {!!Form::hidden('aciertos', 0, array('id' => 'aciertos')) !!}
        {!!Form::hidden('ahorcado[]', 'AHORCADO', array('id' => 'ahorcado')) !!}
        {!!Form::hidden('posiciones', null, array('id' => 'posiciones')) !!}

    <?php
        echo '
        <div id="turnos"></div>
        <hr>
        Fallos: <input id="fallados" type="text" readonly value=""> '.
        '<br>Letras jugadas: <input id="jugados" type="text" readonly value="">'.
        '<div id="palabras" style="font-size: 50px; padding: 5px; margin: 5px; background:#C0C0C0; display:none;">
        
        </div>';
    
    ?>

    </fieldset>
    

	{!! Form::close() !!}
	</div>
</div>
@stop