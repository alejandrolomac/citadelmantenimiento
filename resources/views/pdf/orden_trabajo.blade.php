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
        .header-table,
        .header-table th,
        .header-table td {
            border: none !important;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td>
                <h2>Orden de Trabajo: {{ $no_orden }}</h2>
                <table class="header-table">
                    <tr>
                        <td><strong>Técnico:</strong> {{ $tecnico }}</td>
                    </tr>
                    <tr>
                        <td><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <h3>Información del Dispositivo</h3>
    <table>
        <tr>
            <td><strong>Nombre del Dispositivo:</strong> {{ $nombre }}</td>
            <td><strong>TB ID:</strong> {{ $tb_id }}</td>
        </tr>
    </table>

    @if(!empty($reporte_recibido) || !empty($reporte_adjuntos))
    <h3>Detalles de Reporte</h3>
    <div>
        @if(!empty($reporte_recibido))
            <p style="white-space: pre-line;">{{ $reporte_recibido }}</p>
        @endif

        @if(!empty($reporte_adjuntos))
            @php
                $archivos = json_decode($reporte_adjuntos, true);
            @endphp
            @if(is_array($archivos) && count($archivos) > 0)
                <div style="margin-top: 10px;">
                    @foreach($archivos as $archivo)
                        @php
                            $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpeg', 'jpg', 'png', 'gif']);
                            // Usar ruta absoluta para dompdf
                            $archivoPath = public_path('storage/' . $archivo);
                        @endphp
                        @if($isImage && file_exists($archivoPath))
                            <img src="{{ $archivoPath }}" style="height: 100px; width: 100px; object-fit: cover; margin-right: 10px; border: 1px solid #ccc;">
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
    </div>
    @endif

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
                    Firma Técnico
                </td>
                <td style="text-align: center; width: 50%; border: none;">
                    @if ($firmaConductor)
                        <img src="{{ $firmaConductor }}" alt="Firma Administrador" style="width: 200px; height: auto;">
                        <br>
                        _________________________
                    @else
                        _________________________
                    @endif
                    
                    <br>
                    Firma Administrador
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
