<?php

#Empieza el juego

function iniciarJuego($idCategoria)
{
    $_SESSION['pelicula'] = elegirTitulo($idCategoria);

    for ($i=0; $i < strlen($_SESSION['pelicula'])-2; $i++) 
    { 
        if($_SESSION['pelicula'][$i] === ' ')
            $cifrado[$i] = '&nbsp';
        else
            $cifrado[$i] = ' _ ';
    }

    $_SESSION['cifrado'] = $cifrado;
    $_SESSION['fallos'] = '';
    $_SESSION['letrajugada'] = '';
    $_SESSION['errores'] = 0;
    $_SESSION['aciertos'] = 0;

    $_SESSION['ahorcado'] = [   
                    '<b style="color: black;">A</b>',
                    '<b style="color: black;">H</b>',
                    '<b style="color: black;">O</b>',
                    '<b style="color: black;">R</b>',
                    '<b style="color: black;">C</b>',
                    '<b style="color: black;">A</b>',
                    '<b style="color: black;">D</b>',
                    '<b style="color: black;">O</b>'];

    $_SESSION['disabled'] = '';

    $_SESSION['posiciones'] = array_count_values($_SESSION['cifrado']);
}

function jugar($letra)
{
    $valor = '';
    $estado = false;
    $valida = true;

    if (ctype_alpha($letra) == false && $letra !== 'ñ') 
    {
        echo 'No has ingresado ninguna letra.';
    }
    elseif(letrajugada($letra))
    {
        $valor = 0;
    }
    else
    {
        for ($i=0; $i < count($_SESSION['cifrado']); $i++) 
        { 
            if($_SESSION['pelicula'][$i] == $letra || $_SESSION['pelicula'][$i] == strtoupper($letra))
            {
                $_SESSION['cifrado'][$i] = $letra;
                $_SESSION['aciertos'] +=1;
                $estado = true;
            }
            else
            {
                $valor = 2;
            }
        }

        if($estado == false)
        {
            $_SESSION['errores'] += 1;
            $_SESSION['fallos'] .= $letra;
        }

        if($estado == true)
        {
            $valor = 1;
        }

        if($_SESSION['errores'] == strlen('ahorcado'))
        {
            $valor = 3;
        }

        if($_SESSION['aciertos'] == $_SESSION['posiciones'][' _ '])
        {
            $valor = 4;
        }
    }

    if($valida)
    {
        sacarResultado($valor, $letra);
    }
}

function ahorcar()
{
    for ($i=0; $i < $_SESSION['errores']; $i++) 
    { 
        $posicion_coincidencia = strpos($_SESSION['ahorcado'][$i],'black');
        if($posicion_coincidencia)
        {
            $_SESSION['ahorcado'][$i] = str_replace('black', 'red', $_SESSION['ahorcado'][$i]);
        }
    }
}

function sacarResultado($valor, $letra)
{
    switch ($valor) {
        case '0':
            echo '¡La letra '.$letra.' ya se ha ingreado!';
            break;
        case '1':
            echo '¡Has hacertado la letra '.$letra.'!';
            break;
        case '2':
            echo '¡La letra '.$letra.' no está!';
            ahorcar();
            break;
        case '3':
            echo '¡Turnos agotados. Has perdido!';
            echo 'La palabra era: '.$_SESSION['pelicula'];
            echo '<br><a href="http://'.$_SERVER["HTTP_HOST"].'/hangman">Volver a jugar</a>';
            ahorcar();
            $_SESSION['disabled'] = 'disabled';
            break;
        case '4':
            echo '¡Has ganado!';
            echo '<a href="http://'.$_SERVER["HTTP_HOST"].'/hangman">Volver a jugar</a>';
            $_SESSION['disabled'] = 'disabled';
            break;
    }
}

function elegirTitulo($idCategoria)
{
    $sub = DB::Select('SELECT nombreSubCategoria FROM subcategoria WHERE Categoria_idCategoria = '.$idCategoria);

    $nomb = '';

    for ($i=0; $i < count($sub); $i++) 
    { 
        $n = get_object_vars($sub[$i])['nombreSubCategoria'];
        $nomb .= $n;
        $nomb .= '</br>';
    }


    //creamos un archivo (fopen) extension txt
    $arch = fopen(app_path().'/Clases/nombres.txt', "w");

    // escribimos en el archivo todo el txt del informe (fputs)
    fputs ($arch, $nomb);

    // cerramos el archivo (fclose)
    fclose($arch);

    $nombres = app_path('Clases/nombres.txt');
    $peliculas = file_get_contents($nombres);
    $cont = -1;

    for ($i=0; $i < strlen($peliculas); $i++) 
    { 
        if(ctype_upper($peliculas[$i]))
        {
            $cont++;
            $pelicula[$cont] = '';
        }
        
        $pelicula[$cont] .= $peliculas[$i];
    }

    $pos = rand(0, count($pelicula) -1);

    return $pelicula[$pos];
}

function letrajugada($letra)
{
    $estado = false;

    if (strrpos($_SESSION['letrajugada'], $letra))
        $estado = true;
    else
        $_SESSION['letrajugada'].= $letra;

    return $estado;
}

?>