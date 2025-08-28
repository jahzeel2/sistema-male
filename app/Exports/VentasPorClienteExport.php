<?php

namespace App\Exports;

use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VentasPorClienteExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Usamos exactamente la misma consulta que tienes en tu controlador
        return Cliente::query()
            ->select([
                'clientes.idcliente',
                'clientes.nombre',
                DB::raw('SUM(COALESCE(detalle_ventas.cantidad, 0) + COALESCE(detalle_ventas.subtotal, 0)) as total_comprado_cliente')
            ])
            ->join('ventas', 'clientes.idcliente', '=', 'ventas.cliente_id')
            ->join('detalle_ventas', 'ventas.idventa', '=', 'detalle_ventas.venta_id')
            ->groupBy('clientes.idcliente', 'clientes.nombre')
            ->orderByDesc('total_comprado_cliente')
            ->get();
    }

    /**
     * Define las cabeceras de las columnas en el archivo Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Cliente',
            'Nombre del Cliente',
            'Total Comprado ($)',
        ];
    }

    /**
     * Mapea los datos para cada fila del Excel.
     *
     * @param mixed $cliente
     * @return array
     */
    public function map($cliente): array
    {
        // Aquí personalizamos el formato de cada fila
        return [
            $cliente->idcliente,
            $cliente->nombre,
            // Exportamos el total como un número para que se puedan hacer cálculos en Excel
            $cliente->total_comprado_cliente,
        ];
    }
}
