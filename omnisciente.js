////////////////////////
//DESCUBRIR UN OBJETO
function eureka(objeto) {
  div_consola.innerHTML = "";
  var tmp = document.createElement('h4')
  tmp.innerHTML = "Respuesta:";
  div_consola.appendChild(tmp);

  var tmp = document.createElement('p')
  tmp.innerHTML = "la fruta es "+objeto[0];
  div_consola.appendChild(tmp);
}

function hacer_pregunta(indice) {
  var s="";
  var caracter = caracteristicas[indice];
  s += "¿" + verbos[caracter[0]] + " " + caracter[1] + "?";
  return s;
}


function preguntar(pregunta,i,j,agenda,preguntas) {
  div_consola.innerHTML = "";
  var objeto = agenda[i];
  var propiedades = objeto[1];
  var propiedad = propiedades[j];
  //se desenglosa la dupla
  var caracter = propiedad[0];  //id de la caracteristica
  var polaridad = propiedad[1]  //falso o verdadero
  var tmp = document.createElement('h4')
  tmp.innerHTML = pregunta;
  div_consola.appendChild(tmp);

  var div_respuestas = document.createElement('p')

  var tmp = document.createElement('button')
  tmp.innerHTML = "SI"
  tmp.addEventListener('click',function(){responder(true,i,j,agenda,preguntas)})
  div_respuestas.appendChild(tmp)

  var tmp = document.createElement('button')
  tmp.innerHTML = "NO"
  tmp.addEventListener('click',function(){responder(false,i,j,agenda,preguntas)})
  div_respuestas.appendChild(tmp)

  div_consola.appendChild(div_respuestas);
}

function confirmar(i,j,agenda,preguntas) {
  div_consola.innerHTML = "";
  var objeto = agenda[i];
  var tmp = document.createElement('h4')
  tmp.innerHTML = "¿es " + objeto[0] + ' la respuesta?';
  div_consola.appendChild(tmp);

  var div_respuestas = document.createElement('p')

  var tmp = document.createElement('button')
  tmp.innerHTML = "SI"
  tmp.addEventListener('click',function(){confirmado(true,i,j,agenda,preguntas)})
  div_respuestas.appendChild(tmp)

  var tmp = document.createElement('button')
  tmp.innerHTML = "NO"
  tmp.addEventListener('click',function(){confirmado(false,i,j,agenda,preguntas)})
  div_respuestas.appendChild(tmp)

  div_consola.appendChild(div_respuestas);
}

function confirmado(respuesta,i,j,agenda,preguntas){
  div_consola.innerHTML = "";
  var objeto = agenda[i];
  if (respuesta) {
      //es el objeto!
      eureka(objeto);
    }else{
      //no es el indicado, nos vamos pal siguiente
      siguiente_objeto(i+1,agenda,preguntas)
    }
}

function responder(respuesta,i,j,agenda,preguntas) {
  div_consola.innerHTML = "";
  var objeto = agenda[i];
  var propiedades = objeto[1];
  var propiedad = propiedades[j];
  //se desenglosa la dupla
  var caracter = propiedad[0];  //id de la caracteristica
  var polaridad = propiedad[1]  //falso o verdadero
  //luego de tener la respuesta
  preguntas[caracter] = respuesta
  //verifica, si no se cumple
  //pasa al siguiente objeto
  if (respuesta != polaridad) {
    siguiente_objeto(i+1,agenda,preguntas)
  }else{
    //si se cumple, pasa a la siguiente propiedad
    siguiente_pregunta(j+1,i,agenda,preguntas);
  }
}

function siguiente_pregunta(j,i,agenda,preguntas) {
  var objeto = agenda[i];
  var propiedades = objeto[1];
  if (j < propiedades.length) {
    var propiedad = propiedades[j];
    //se desenglosa la dupla
    var caracter = propiedad[0];  //id de la caracteristica
    var polaridad = propiedad[1]  //falso o verdadero
    //antes de preguntar valida si eso ya se ha preguntado
    if (preguntas.hasOwnProperty(caracter) ) {
      //como la pregunta ya existe
      var respuesta = preguntas[caracter];
      responder(respuesta,i,j,agenda,preguntas);
    }else{
      //como no ha preguntado, ahora si pregunta
      preguntar(hacer_pregunta(caracter),i,j,agenda,preguntas);
    }
    //aqui se espera por la respuesta
    //va a otra funcion
  }else{
    //no hay mas propiedades
    //se valida si es el objeto indicado
    confirmar(i,j,agenda,preguntas);
  }
}

function siguiente_objeto(i,agenda,preguntas) {
  if (i < agenda.length) {
    var objeto = agenda[i];
    //se hace la primera pregunta
    siguiente_pregunta(0,i,agenda,preguntas);
  }else{
    //no hay mas objetos
    div_consola.innerHTML = "";
    var tmp = document.createElement('h4')
    tmp.innerHTML = "Respuesta:";
    div_consola.appendChild(tmp);

    var tmp = document.createElement('p')
    tmp.innerHTML = "Puedo saber muchas cosas pero, pero no conozco una fruta que:";
    div_consola.appendChild(tmp);

    var ul_propiedades = document.createElement('ul')

    for (var indice in preguntas) {
      //las duplas tienen:
      //0. el id de la caracteristica
      //1. la polaridad (true/false)
      var caracter = caracteristicas[indice];
      if (preguntas[indice]) {
        var s = verbos[caracter[0]] + " " + caracter[1];
      }else{
        var s = "no " + verbos[caracter[0]] + " " + caracter[1];
      }
      var tmp = document.createElement('li')
      tmp.innerHTML = s;
      ul_propiedades.appendChild(tmp);
    }
    div_consola.appendChild(ul_propiedades);
  }
}

function descubrir_objeto() {
  div_consola.innerHTML = "";

  var preguntas = {} //diccionario con: id caracteristica y respuesta
  //se crea una copia de los objetos
  var agenda = objetos.slice();

  siguiente_objeto(0,agenda,preguntas);
}

function contar_frutas() {
    div_consola.innerHTML = "";
    var tmp = document.createElement('h4')
    tmp.innerHTML = "Frutas:";
    div_consola.appendChild(tmp);

    var tmp = document.createElement('p')
    tmp.innerHTML = "En toda mi experiencia como campesino he conocido <strong>"+ objetos.length + "</strong> clases diferentes de frutas.";
    div_consola.appendChild(tmp);
}

