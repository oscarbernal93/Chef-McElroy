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

//////////////////
// SAVE & LOAD

function descargar() {
  datos = { verbos:verbos,
            caracteristicas:caracteristicas,
            objetos:objetos}
  var a = document.createElement("a"),
  file = new Blob([JSON.stringify(datos)], {type: "application/json"});
  var url = URL.createObjectURL(file);
  a.href = url;
  a.download = "datos.json";
  div_consola.appendChild(a);
  a.click();
  setTimeout(function() {
      div_consola.removeChild(a);
      window.URL.revokeObjectURL(url);  
  }, 0); 
}

function cargador() {
  div_consola.innerHTML = "";
  var tmp = document.createElement('h4')
  tmp.innerHTML = "Cargar base de conocimiento";
  div_consola.appendChild(tmp);

  var input = document.createElement('input')
  input.type="file";
  input.accept=".json, application/json";
  input.innerHTML = "";
  div_consola.appendChild(input);

  input.addEventListener('change', cargar, false);
}


function cargar(evt) {
  var archivo = evt.target.files[0];
  ready=false;
  var reader = new FileReader();
  reader.onload = function(e) {
      contenidos = e.target.result;
      jason = JSON.parse(contenidos);
      verbos = jason.verbos;
      caracteristicas = jason.caracteristicas;
      objetos = jason.objetos;
      ready = true;
  };
  reader.readAsText(archivo);
  while(ready){}
  //mensaje exitoso
  div_consola.innerHTML = "";
  var tmp = document.createElement('h4')
  tmp.innerHTML = "Archivo Cargado!";
  div_consola.appendChild(tmp);
}