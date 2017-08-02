<?php

session_start();

$letra = $_POST['letra'];
jugar($letra);

function jugar($letra)
{
    $valor = '';
    $estado = false;
    $valida = true;

    if (ctype_alpha($letra) == false && $letra !== 'ñ') 
    {
        echo json_encode('No has ingresado ninguna letra.');

    }
    elseif(letrajugada($letra))
    {
        $valor = 0;
    }
    else
    {
        for ($i=0; $i < count($_SESSION['cifrado']); $i++) 
        { 
            if($_SESSION['palabra'][$i] == $letra || $_SESSION['palabra'][$i] == strtoupper($letra))
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
    $resultado = '';

    switch ($valor) 
    {
        case '0':
            $resultado = '¡La letra '.$letra.' ya se ha ingreado!';
            break;
        case '1':
            $resultado = '¡Has hacertado la letra '.$letra.'!';
            break;
        case '2':
            $resultado = '¡La letra '.$letra.' no está!';
            ahorcar();
            break;
        case '3':
            $resultado .= '¡Turnos agotados. Has perdido!';
            $resultado .= 'La palabra era: '.$_SESSION['palabra'];
            $resultado .= '<br><a href="http://'.$_SERVER["HTTP_HOST"].'/hangman">Volver a jugar</a>';
            ahorcar();
            $_SESSION['disabled'] = 'disabled';
            break;
        case '4':
            $resultado .= '¡Has ganado!';
            $resultado .= '<a href="http://'.$_SERVER["HTTP_HOST"].'/hangman">Volver a jugar</a>';
            $_SESSION['disabled'] = 'disabled';
            break;
    }

    echo json_encode($resultado);
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