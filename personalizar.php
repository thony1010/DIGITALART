<?php
$producto = [
  "id" => 1,
  "nombre" => "Cargador Personalizable",
  "precio" => 25.00,
  "imagen" => "cargador.png"
];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Personaliza tu <?= $producto['nombre'] ?></title>
  <style>
    .editor { display: flex; gap: 20px; margin: 30px; }
    .preview { position: relative; }
    #canvas { border: 2px solid #ccc; border-radius: 12px; }
    .controls { display: flex; flex-direction: column; gap: 10px; }
    button, input, select {
      padding: 8px; border-radius: 6px; border: 1px solid #ccc;
    }
    button { background: #0f62fe; color: white; cursor: pointer; }
    button:hover { background: #0043c9; }
  </style>
</head>
<body>
  <header>
    <h1>⚡ Personaliza tu <?= $producto['nombre'] ?></h1>
  </header>

  <div class="editor">
    <div class="preview">
      <canvas id="canvas" width="300" height="450"></canvas>
    </div>

    <div class="controls">
      <label>Texto:</label>
      <input type="text" id="texto" placeholder="Ej: Juanito Power">
      <button onclick="addText()">Agregar texto</button>

      <label>Sube imagen/logo:</label>
      <input type="file" id="imagenInput" accept="image/*">

      <hr>

      <label>Cambiar color de texto:</label>
      <input type="color" id="colorPicker" value="#000000" onchange="changeColor()">

      <label>Tamaño de letra:</label>
      <select id="fontSize" onchange="changeFontSize()">
        <option value="16">16px</option>
        <option value="20" selected>20px</option>
        <option value="30">30px</option>
        <option value="40">40px</option>
      </select>

      <hr>

      <button onclick="duplicate()">Duplicar</button>
      <button onclick="removeObj()">Eliminar</button>
      <button onclick="guardar()">Guardar diseño</button>
      <button onclick="gdise()">descargar edicion</button>
<br>
      <input type="file" id="upload-json" accept=".json" >
    </div>
  </div>

  <!-- Fabric.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
  <script>
    const canvas = new fabric.Canvas('canvas');

    // Fondo del cargador
    fabric.Image.fromURL('<?= $producto['imagen'] ?>', function(img) {
      img.scaleToWidth(300);
      canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
    });

    // Agregar texto
    function addText() {
      const input = document.getElementById("texto").value;
      if (input.trim() !== "") {
        const text = new fabric.Textbox(input, {
          left: 50,
          top: 400,
          fontSize: 20,
          fill: "#000000",
          editable: true
        });
        canvas.add(text).setActiveObject(text);
      }
    }

    // Subir imagen
    document.getElementById("imagenInput").addEventListener("change", (e) => {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.onload = function(event) {
        fabric.Image.fromURL(event.target.result, function(img) {
          img.scaleToWidth(150);
          img.set({ left: 75, top: 150 });
          canvas.add(img).setActiveObject(img);
        });
      };
      reader.readAsDataURL(file);
    });

    // Cambiar color del texto seleccionado
    function changeColor() {
      const obj = canvas.getActiveObject();
      if (obj && obj.type === "textbox") {
        obj.set("fill", document.getElementById("colorPicker").value);
        canvas.renderAll();
      }
    }

    // Cambiar tamaño de letra
    function changeFontSize() {
      const obj = canvas.getActiveObject();
      if (obj && obj.type === "textbox") {
        obj.set("fontSize", parseInt(document.getElementById("fontSize").value));
        canvas.renderAll();
      }
    }

    // Duplicar objeto seleccionado
    function duplicate() {
      const obj = canvas.getActiveObject();
      if (obj) {
        obj.clone(function(clone) {
          clone.set({ left: obj.left + 20, top: obj.top + 20 });
          canvas.add(clone).setActiveObject(clone);
        });
      }
    }

    // Eliminar objeto seleccionado
    function removeObj() {
      const obj = canvas.getActiveObject();
      if (obj) {
        canvas.remove(obj);
      }
    }

    /* Guardar diseño
    function guardar() {

       const obj = canvas.getActiveObject();
      const dataURL = canvas.toDataURL("image/png");
      console.log("Diseño exportado:", dataURL);
      alert("Tu diseño ha sido guardado (simulado)");
  
    */
   function guardar() {
  const obj = canvas.getActiveObject();
  if (!obj) {
    alert("Selecciona un objeto para guardar.");
    return;
  }

  // Crear un canvas temporal
  const tempCanvas = new fabric.Canvas(null, {
    width: obj.width * obj.scaleX,
    height: obj.height * obj.scaleY
  });

  // Clonar el objeto y agregarlo al canvas temporal
  obj.clone(function(clone) {
    clone.set({ left: 0, top: 0 });
    tempCanvas.add(clone);
    tempCanvas.renderAll();

    // Asumiendo que tienes un canvas de Fabric.js llamado `canvas`
const dataURL = canvas.toDataURL({
  format: 'png', // o 'jpeg'
  quality: 1.0
});

// Crear un enlace de descarga
const link = document.createElement('a');
link.href = dataURL;
link.download = 'diseño.png';
link.click();

    alert("Tu diseño ha sido creado");
 
  });
}

function gdise() {  
    
  const json = JSON.stringify(canvas.toJSON());

// Crear un archivo y descargarlo
const blob = new Blob([json], { type: 'application/json' });
const link = document.createElement('a');
link.href = URL.createObjectURL(blob);
link.download = 'diseño.json';
link.click();
alert("Tu diseño ha sido guardado como JSON");
   } 


   // Cargar diseño desde JSON
   document.getElementById('upload-json').addEventListener('change', function (e) {
  const file = e.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = function (event) {
    const json = event.target.result;

    // Limpiar el canvas actual si es necesario
    canvas.clear();

    // Cargar el JSON en el canvas
    canvas.loadFromJSON(json, function () {
      canvas.renderAll(); // Renderiza el nuevo contenido
    });
  };

  reader.readAsText(file);
});


  </script>
  
</body>
</html>
