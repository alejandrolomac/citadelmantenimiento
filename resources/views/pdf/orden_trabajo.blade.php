<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Orden de Trabajo</title>
    <style>
        body 
        {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table 
        {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td 
        {
            border: 1px solid #000;
        }

        th,
        td 
        {
            padding: 8px;
            text-align: left;
        }

        h2,
        h3 
        {
            text-align: center;
        }

        .tecnico 
        {
            margin-top: 30px;
            text-align: right;
        }

        .signature 
        {
            margin-top: 40px;
            text-align: center;
        }

        .qr-container 
        {
            width: 100px;
            height: 100px;
            float: left;
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <table>
        <tr>
            <td class="qr-container">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="Código QR"
                    style="width: 100px; height: 100px;">
            </td>
            <td>
                <h2>Orden de Trabajo: {{ $no_orden }}</h2>
                <table>
                    <tr>
                        <td><strong>Técnico:</strong> {{ $tecnico }}</td>
                        <td><strong>Hora de Inicio:</strong> {{ $hora_inicio }}</td>
                    </tr>
                    <tr>
                        <td><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</td>
                        <td><strong>Hora de Finalización:</strong> {{ $hora_final }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Ubicación:</strong> {{ $ubicacion_unidad }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <h3>Información de la Unidad</h3>
    <table>
        <tr>
            <td><strong>Unidad:</strong> {{ $type }}</td>
            <td><strong>Conductor:</strong> {{ $conductor }}</td>
        </tr>
        <tr>
            <td><strong>Nombre de Máquina/Unidad:</strong> {{ $nombre }}</td>
            <td><strong>TB ID:</strong> {{ $tb_id }}</td>
        </tr>
        <tr>
            <td><strong>Fecha:</strong> {{ $fecha }}</td>
            <td> {{ $kilometraje }}</td>
        </tr>
    </table>

    <h3>Trabajo Realizado</h3>
    <div>
        <p style="white-space: pre-line;">{{ $detalles ?? 'Sin detalles.' }}</p>
    </div>


    <div class="signature">
        <table style="width: 100%; text-align: center; margin-top: 30px; border: 1px solid transparent; ">
            <tr>
                <td style="text-align: center; width: 50%; border: none;">
                    @if ($firmaTecnico)
                        <img src="{{ $firmaTecnico }}" alt="Firma Técnico" style="width: 200px; height: auto;">
                        <br>
                        _________________________
                    @else
                        _________________________
                    @endif
                    
                    <br>
                    Firma del Técnico
                </td>
                <td style="text-align: center; width: 50%; border: none;">
                    @if ($firmaConductor)
                        <img src="{{ $firmaConductor }}" alt="Firma Conductor" style="width: 200px; height: auto;">
                        <br>
                        _________________________
                    @else
                        _________________________
                    @endif
                    
                    <br>
                    Firma del Conductor
                </td>
            </tr>
        </table>
        <p style="text-align: center; margin-top: 10px;">
            Al firmar, se aceptan los términos y condiciones relacionados con los trabajos realizados en la Unidad.
            Escanea el QR para ver más detalles sobre la orden de trabajo en línea.
        </p>
    </div>

</body>

</html>
