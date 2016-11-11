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

/////////////////////////
//CREAR UN OBJETO NUEVO
function crear_objeto() {
    var propiedades = [];
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




//////////////////////
//CORRE EL PROGRAMA
var un_objeto_mas = confirm("existen " + objetos.length + " objetos en la base de conocimiento\n¿Desea adicionar un objeto?");
while (un_objeto_mas){
  crear_objeto();
  un_objeto_mas = confirm("¿Adicionar otro objeto?");
}
alert("Soy Chucho, una pepa en frutas...");
descubrir_objeto();
