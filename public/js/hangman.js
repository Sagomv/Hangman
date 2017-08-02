function iniciarJuego(idCategoria)
{
    var token = document.getElementById('token').value;
        $.ajax({
            headers: {'X-CSRF-TOKEN': token},
            dataType: "json",
            data: {'idCategoria': idCategoria},
            url:   'http://'+location.host+'/iniciarJuego/',
            type:  'post',
            beforeSend: function(){
                },
            success: function(respuesta)
            {
                $("#letra").prop('disabled',false);

                cifrado = " _, ".repeat(respuesta.length);

                cifrado = cifrado.split(',');
                ahorcado = Array('A', 'H', 'O', 'R', 'C', 'A', 'D', 'O');

                $("#ahorcado").val(ahorcado);

                posiciones = cifrado.length-1;

                linea = '';

                for (var i = 0; i < posiciones; i++)
                {
                    linea += ' _ ';
                }

                cifrado = Array(cifrado);

                $("#palabra").val(respuesta);
                $("#cifrado").val(cifrado);
                $("#posiciones").val(posiciones);

                $("#palabras").html(linea);

                $("#palabras").css('display','block');
            },
            error: function(xhr,err)
            { 
                alert("Error");
            }
        });
}

function jugar(letra)
{

    valor = '';
    estado = false;
    valida = true;
    palabra = $("#palabra").val();
    ahorcado = $("#ahorcado").val();
    ahorcado = ahorcado.split(',');

    if (letra == '') 
    {
        alert('No has ingresado ninguna letra.');
    }
    else if(letraescrita(letra))
    {
        valor = 0;
    }
    else
    {
        for (i=0; i < $("#posiciones").val(); i++) 
        { 
            if(palabra[i] == letra || palabra[i] == letra.toUpperCase())
            {
                cifrado = $("#cifrado").val();

                cifrado = cifrado.split(',');

                cifrado[i] = letra;
                
                $("#cifrado").val(cifrado);
                $("#palabras").html(cifrado);

                estado = true;

                aciertos = $("#aciertos").val();
                aciertos = parseInt(aciertos) + 1;
                $("#aciertos").val(aciertos);
            }
            else
            {
                valor = 2;
            }
        }

        if(estado == false)
        {
            errores = $("#errores").val();
            errores = parseInt(errores) + 1;
            fallos = letra;

            $("#errores").val(errores);
            $("#fallos").val(fallos+ $("#fallos").val());
            $("#fallados").val($("#fallos").val());
        }

        if(estado == true)
        {
            valor = 1;
        }

        if($("#errores").val() == ahorcado.length)
        {
            valor = 3;
        }

        if($("#aciertos").val() == $("#posiciones").val())
        {
            valor = 4;
        }
    }

    if(valida)
    {
        sacarResultado(valor, letra);
    }
}

function sacarResultado(valor, letra)
{
    palabra = $("#palabra").val();
    switch (valor) 
    {
        case 0:
            alert('¡La letra '+letra+' ya se ha ingreado!');
            break;

        case 1:
            alert('¡Has hacertado la letra '+letra+'!');
            break;

        case 2:
            alert('¡La letra '+letra+' no está!');
            ahorcar();
            break;

        case 3:
            alert('¡Turnos agotados. Has perdido!');
            alert('La palabra era: '+palabra+'. Presione aceptar para iniciar un nuevo juego.');
            location.reload();
            ahorcar();
            break;

        case 4:
            alert('¡Has ganado! Presione aceptar para iniciar un nuevo juego.');
            location.reload();
            break;
    }
}

function ahorcar()
{
    ahorcado = $("#ahorcado").val();
    ahorcado = ahorcado.split(',');

    turno = '';
    turno += '<h2 style="color:red">';

    for (i=0; i < $("#errores").val(); i++) 
    { 
        turno += ahorcado[i]+' ';
    }

    turno += '</h2>'

    $("#turnos").html(turno);
}

function letraescrita(letra)
{
    estado = false;

    letrajugada = $("#letrajugada").val();
    
    if (letrajugada.indexOf(letra))
    {
        $("#letrajugada").val(letra+ $("#letrajugada").val());
        $("#jugados").val($("#letrajugada").val());
    }
    else
        estado = true;

    return estado;
}

function soloLetras(e)
{
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " abcdefghijklmnñopqrstuvwxyz";
    especiales = "8-37-39-46";

    tecla_especial = false
    for(var i in especiales)
    {
        if(key == especiales[i])
        {
            tecla_especial = true;
            break;
        }
    }

    if(letras.indexOf(tecla)==-1 && !tecla_especial)
    {
        return false;
    }
}