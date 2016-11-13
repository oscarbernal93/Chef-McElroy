//funcion que convierte 
function parseBool(cadena){
  if (cadena == "true") {
    return true;
  }else{
    return false;
  }
}

function guardar_propiedad(nombre,propiedades){
  objetos.push([nombre.value,propiedades]);
  div_consola.innerHTML="";
  var tmp = document.createElement('h4')
  tmp.innerHTML = "datos de " + nombre.value + " guardados correctamente";
  div_consola.appendChild(tmp);
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
  tmp.innerHTML = "aceptar";
  tmp.addEventListener('click',function(){
    verbo_nuevo=parseInt(select.value);
    cualidad_nueva=input.value;
    caracteristicas.push([verbo_nuevo,cualidad_nueva]);

    indice_seleccionado = caracteristicas.length - 1;
    polaridad = parseBool(select_respuesta.value);
    propiedades.push([indice_seleccionado,polaridad]);

    var tmp = document.createElement('li')
    tmp.value = caracteristicas.length - 1;
    tmp.innerHTML = "(" + select_respuesta.selectedOptions[0].innerHTML + ") "+verbos[verbo_nuevo] + " " + cualidad_nueva;
    lista.appendChild(tmp); 

    contenedor.innerHTML="";
    //alert("guardado");
    var tmp = document.createElement('button')
    tmp.innerHTML = "Seleccionar propiedad"
    tmp.addEventListener('click',function(){seleccionar_propiedad(contenedor,lista,nombre,propiedades)})
    contenedor.appendChild(tmp)
    var tmp = document.createElement('button')
    tmp.innerHTML = "Crear propiedad"
    tmp.addEventListener('click',function(){crear_propiedad(contenedor,lista,nombre,propiedades)})
    contenedor.appendChild(tmp)
    var tmp = document.createElement('button')
    tmp.innerHTML = "Guardar Fruta"
    tmp.addEventListener('click',function(){guardar_propiedad(nombre,propiedades)})
    contenedor.appendChild(tmp)
  })
  contenedor.appendChild(select_respuesta);
  contenedor.appendChild(select);
  contenedor.appendChild(input);
  contenedor.appendChild(tmp);
}

function seleccionar_propiedad(contenedor,lista,nombre,propiedades){
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
  tmp.innerHTML = "aceptar";
  tmp.addEventListener('click',function(){
    indice_seleccionado = parseInt(select.value);
    polaridad = parseBool(select_respuesta.value);
    propiedades.push([indice_seleccionado,polaridad]);
    m = caracteristicas[indice_seleccionado];
    lista.children[indice_seleccionado].innerHTML = "(" + select_respuesta.selectedOptions[0].innerHTML + ") "+ verbos[m[0]] + " " + m[1];
    contenedor.innerHTML="";
    //alert("guardado");
    var tmp = document.createElement('button')
    tmp.innerHTML = "Seleccionar propiedad"
    tmp.addEventListener('click',function(){seleccionar_propiedad(contenedor,lista,nombre,propiedades)})
    contenedor.appendChild(tmp)
    var tmp = document.createElement('button')
    tmp.innerHTML = "Crear propiedad"
    tmp.addEventListener('click',function(){crear_propiedad(contenedor,lista,nombre,propiedades)})
    contenedor.appendChild(tmp)
    var tmp = document.createElement('button')
    tmp.innerHTML = "Guardar Fruta"
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
    tmp.addEventListener('click',function(){seleccionar_propiedad(consola_propiedades,ul_propiedades,nombre,propiedades)})
    consola_propiedades.appendChild(tmp)

    var tmp = document.createElement('button')
    tmp.innerHTML = "Crear propiedad"
    tmp.addEventListener('click',function(){crear_propiedad(consola_propiedades,ul_propiedades,nombre,propiedades)})
    consola_propiedades.appendChild(tmp)

    var tmp = document.createElement('button')
    tmp.innerHTML = "Guardar Fruta"
    tmp.addEventListener('click',function(){guardar_propiedad(nombre,propiedades)})
    consola_propiedades.appendChild(tmp)
}
