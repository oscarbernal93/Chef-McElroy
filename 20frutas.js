///////////////////////
//ELEMENTO CONTENEDOR
var div_consola = document.getElementById('consola');
///////////////////////////
//BASE DE CONOCIMIENTO
var verbos = ["es","tiene","ha"];
//estos son los adjetivos (rojo,con semillas, de arbol,etc..)
var caracteristicas = [ 
      //verbo y cualidad
      [0,"roja"], //0
      [1,"varias semillas"], //1
      [2,"crecido en un arbol"], //2
    ]; 
var objetos = [
        //nombre y propiedades
        //las propiedades son un array de duplas
        //cada dupla tiene un id y una polaridad
        ["naranja",[
            [0,false],
            [1,true],
            [2,true],
        ]],
        ["manzana",[
            [0,true],
            [1,true],
            [2,true],
        ]],
        ["pera",[
            [0,false],
            [1,true],
            [2,true],
        ]],
        ["mango",[
            [0,false],
            [1,false],
            [2,true],
        ]],
        ["lulo",[
            [0,false],
            [1,true],
            [2,false],
        ]],
        ["fresa",[
            [0,true],
            [1,true],
            [2,false],
        ]],
    ];

/////////////////////////
//FUNCIONES DE IMPRESION
function imprimir_caracteristicas() {
  var s = '';
  for (var l in caracteristicas) {
    m = caracteristicas[l];
    s += l + ". " + verbos[m[0]] + " " + m[1] + ';\n';
  }
  return s;
}
function imprimir_verbos() {
  var s = '';
  for (var l in verbos) {
    m = verbos[l];
    s += l + ". " + m + ';\n';
  }
  return s;
}
function hacer_pregunta(indice) {
  var s="";
  var caracter = caracteristicas[indice];
  s += "¿" + verbos[caracter[0]] + " " + caracter[1] + "?";
  return s;
}
function decir_propiedades(propiedades) {
  var s = "";
  for (var indice in propiedades) {
    dupla=propiedades[indice];
    //las duplas tienen:
    //0. el id de la caracteristica
    //1. la polaridad (true/false)
    var caracter = caracteristicas[dupla[0]];
    if (dupla[1]) {
      s += verbos[caracter[0]] + " " + caracter[1];
    }else{
      s += "no " + verbos[caracter[0]] + " " + caracter[1];
    }
    s += "\n";
  }
  return s;
}
function decir_respuestas(respuestas) {
  var s = "";
  for (var indice in respuestas) {
    //las duplas tienen:
    //0. el id de la caracteristica
    //1. la polaridad (true/false)
    var caracter = caracteristicas[indice];
    if (respuestas[indice]) {
      s += verbos[caracter[0]] + " " + caracter[1];
    }else{
      s += "no " + verbos[caracter[0]] + " " + caracter[1];
    }
    s += "\n";
  }
  return s;
}

function guardar_propiedad(nombre,propiedades){
  objetos.push([nombre.value,propiedades]);
  alert(nombre.value + " se ha guardado!");
  div_consola.innerHTML="";
}

function crear_propiedad(contenedor,lista,nombre,propiedades){
  contenedor.innerHTML = "";
  var select = document.createElement('select')
  for (var i in verbos) {
    var verbo = verbos[i]
    var tmp = document.createElement('option')
    tmp.value = i;
    tmp.innerHTML = verbo;
    select.appendChild(tmp); 
  }
  var select_respuesta = document.createElement('select')
  var tmp = document.createElement('option')
    tmp.value = true;
    tmp.innerHTML = "si";
  select_respuesta.appendChild(tmp); 
  var tmp = document.createElement('option')
    tmp.value = false;
    tmp.innerHTML = "no";
  select_respuesta.appendChild(tmp);

  var input = document.createElement('input')
  var tmp = document.createElement('button')
  tmp.innerHTML = "guardar";
  tmp.addEventListener('click',function(){
    verbo_nuevo=select.value;
    cualidad_nueva=input.value;
    caracteristicas.push([verbo_nuevo,cualidad_nueva]);

    indice_seleccionado = caracteristicas.length - 1;
    polaridad = select_respuesta.value;
    propiedades.push([indice_seleccionado,polaridad]);

    var tmp = document.createElement('li')
    tmp.value = caracteristicas.length - 1;
    tmp.innerHTML = "(" + select_respuesta.selectedOptions[0].innerHTML + ") "+verbos[verbo_nuevo] + " " + cualidad_nueva;
    lista.appendChild(tmp); 

    contenedor.innerHTML="";
    //alert("guardado");
    var tmp = document.createElement('button')
    tmp.innerHTML = "Seleccionar propiedad"
    tmp.addEventListener('click',function(){sleccionar_propiedad(contenedor,lista,nombre,propiedades)})
    contenedor.appendChild(tmp)
    var tmp = document.createElement('button')
    tmp.innerHTML = "Crear propiedad"
    tmp.addEventListener('click',function(){crear_propiedad(contenedor,lista,nombre,propiedades)})
    contenedor.appendChild(tmp)
    var tmp = document.createElement('button')
    tmp.innerHTML = "Crear propiedad"
    tmp.addEventListener('click',function(){guardar_propiedad(nombre,propiedades)})
    contenedor.appendChild(tmp)
  })
  contenedor.appendChild(select_respuesta);
  contenedor.appendChild(select);
  contenedor.appendChild(input);
  contenedor.appendChild(tmp);
}

function sleccionar_propiedad(contenedor,lista,nombre,propiedades){
  contenedor.innerHTML = "";
  var select = document.createElement('select')
  for (var i in caracteristicas) {
    var caracter = caracteristicas[i]
    var tmp = document.createElement('option')
    tmp.value = i;
    tmp.innerHTML = verbos[caracter[0]] + " " + caracter[1];
    select.appendChild(tmp); 
  }
  var select_respuesta = document.createElement('select')
  var tmp = document.createElement('option')
    tmp.value = true;
    tmp.innerHTML = "si";
  select_respuesta.appendChild(tmp); 
  var tmp = document.createElement('option')
    tmp.value = false;
    tmp.innerHTML = "no";
  select_respuesta.appendChild(tmp);

  var tmp = document.createElement('button')
  tmp.innerHTML = "guardar";
  tmp.addEventListener('click',function(){
    indice_seleccionado = select.value;
    polaridad = select_respuesta.value;
    propiedades.push([indice_seleccionado,polaridad]);
    m = caracteristicas[indice_seleccionado];
    lista.children[indice_seleccionado].innerHTML = "(" + select_respuesta.selectedOptions[0].innerHTML + ") "+ verbos[m[0]] + " " + m[1];
    contenedor.innerHTML="";
    //alert("guardado");
    var tmp = document.createElement('button')
    tmp.innerHTML = "Seleccionar propiedad"
    tmp.addEventListener('click',function(){sleccionar_propiedad(contenedor,lista,nombre,propiedades)})
    contenedor.appendChild(tmp)
    var tmp = document.createElement('button')
    tmp.innerHTML = "Crear propiedad"
    tmp.addEventListener('click',function(){crear_propiedad(contenedor,lista,nombre,propiedades)})
    contenedor.appendChild(tmp)
    var tmp = document.createElement('button')
    tmp.innerHTML = "Guardar"
    tmp.addEventListener('click',function(){guardar_propiedad(nombre,propiedades)})
    contenedor.appendChild(tmp)
  })
  contenedor.appendChild(select_respuesta);
  contenedor.appendChild(select);
  contenedor.appendChild(tmp);
}
/////////////////////////
//CREAR UN OBJETO NUEVO
function crear_objeto() {

    var propiedades = [];
    div_consola.innerHTML="";

    var div_propiedades = document.createElement('div');

    var tmp = document.createElement('h4')
    tmp.innerHTML = "Nombre:";
    div_propiedades.appendChild(tmp);

    var nombre = document.createElement('input')
    div_propiedades.appendChild(nombre);

    var tmp = document.createElement('h4')
    tmp.innerHTML = "Propiedades:";
    div_propiedades.appendChild(tmp);
    var ul_propiedades = document.createElement('ul')
    
    for (var l in caracteristicas) {
        m = caracteristicas[l];
        var tmp = document.createElement('li')
        tmp.innerHTML = verbos[m[0]] + " " + m[1];
        ul_propiedades.appendChild(tmp);
      }

    div_propiedades.appendChild(ul_propiedades)

    var consola_propiedades= document.createElement('div')
    div_propiedades.appendChild(consola_propiedades)
    
    div_consola.appendChild(div_propiedades);

    var tmp = document.createElement('button')
    tmp.innerHTML = "Seleccionar propiedad"
    tmp.addEventListener('click',function(){sleccionar_propiedad(consola_propiedades,ul_propiedades,nombre,propiedades)})
    consola_propiedades.appendChild(tmp)

    var tmp = document.createElement('button')
    tmp.innerHTML = "Crear propiedad"
    tmp.addEventListener('click',function(){crear_propiedad(consola_propiedades,ul_propiedades,nombre,propiedades)})
    consola_propiedades.appendChild(tmp)

    var tmp = document.createElement('button')
    tmp.innerHTML = "Guardar"
    tmp.addEventListener('click',function(){guardar_propiedad(nombre,propiedades)})
    consola_propiedades.appendChild(tmp)

/*
    var una_mas = true;
    while (una_mas){
      var es_nuevo = confirm("Caracteristicas existentes:\n" + imprimir_caracteristicas() +"\n¿La caracteristica es nueva?");
      if (es_nuevo) {
        var verbo_nuevo = prompt("verbos existentes:\n" + imprimir_verbos() + "\n¿que verbo?");
        var cualidad_nueva = prompt("Ingrese la cualidad:\nla fruta "+verbos[verbo_nuevo]+"...");
        caracteristicas.push([verbo_nuevo,cualidad_nueva]);
        indice_seleccionado = caracteristicas.length -1;
      }else{
        var indice_seleccionado = prompt("Caracteristicas existentes:\n" + imprimir_caracteristicas() +"\n¿Que caracteristica?");
      }
      var polaridad = confirm("responda la pregunta:\n" + hacer_pregunta(indice_seleccionado));
      //la lista de propiedades tiede duplas
      propiedades.push([indice_seleccionado,polaridad]);
      una_mas = confirm("¿Adicionar otra caracteristica?");
    }
    var nombre = prompt(decir_propiedades(propiedades)+"\n¿Cual es el nombre?");
    objetos.push([nombre,propiedades]);
  */ 
}

////////////////////////
//DESCUBRIR UN OBJETO
function descubrir_objeto() {
  var encontrado = false;
  var preguntas = {} //diccionario con: id caracteristica y respuesta
  //se crea una copia de los objetos
  var agenda = objetos.slice();
  for (var i in agenda) { 
    var objeto = agenda[i];
    //se supone que es el primer objeto
    //y empieza a preguntar caracteristicas
    propiedades = objeto[1];
    encontrado = true;
    for (var j in propiedades){
      propiedad = propiedades[j];
      //se desenglosa la dupla
      caracter = propiedad[0];  //id de la caracteristica
      polaridad = propiedad[1]  //falso o verdadero
      //antes de preguntar valida si eso ya se ha preguntado
      if (preguntas.hasOwnProperty(caracter) ) {
        //como la pregunta ya existe
        respuesta = preguntas[caracter];
      }else{
        //como no ha preguntado, ahora si pregunta
        var respuesta = confirm(hacer_pregunta(caracter));
        //y guarda la respuesta
        preguntas[caracter] = respuesta
      }
      //se valida la polaridad, si falla, borra el item y se sale
      if (respuesta != polaridad) {
        //con un solo caracter que falle, descarta el item
        encontrado = false;
        delete agenda[i];
        break;
      }
    }
    //si llega al final del ciclo y lo encontro
    //valida
    if (encontrado){
      encontrado = confirm("¿es " + objeto[0] + ' la respuesta?');
      //se recontra valida
      if (encontrado) {
        //y se sale
        console.log("es: "+objeto[0]);
        break;
      }
    }
  }
  //si despues de todo no la encontró
  if (!encontrado){
    alert("No se encontraron coincidencias para las caracteristicas:\n"+decir_respuestas(preguntas));
  }
}

