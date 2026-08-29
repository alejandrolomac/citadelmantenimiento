<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Unidad</title>
</head>
<body>
    <h1>Crear Nueva Unidad</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="/nueva-unidad" method="POST">
        @csrf
        <label for="tipo_vehiculo">Tipo:</label>
        <input type="text" name="tipo_vehiculo" id="tipo_vehiculo">

        <label for="codigo_vehiculo">Nombre:</label>
        <input type="text" name="nombre" id="nombre">

        <label for="kilometraje">Kilometraje:</label>
        <input type="text" name="kilometraje" id="kilometraje">

        <label for="ubicacion_unidad">Ubicación de la Unidad:</label>
        <input type="text" name="ubicacion_unidad" id="ubicacion_unidad">

        <label for="folio">Folio:</label>
        <input type="text" name="folio" id="folio">

        <label for="estado">Estado:</label>
        <select name="estado" id="estado">
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
        </select>

        <button type="submit">Guardar Unidad</button>
    </form>
</body>
</html>
