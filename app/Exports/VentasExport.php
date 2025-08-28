<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VentasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Se consultan las ventas y se unen con clientes para obtener el nombre.
        return DB::table('ventas as v')
            ->join('clientes as c', 'v.cliente_id', '=', 'c.idcliente')
            ->select(
                'v.idventa',
                'v.fecha_hora',
                'v.num_folio',
                'v.tipo_comprobante',
                'v.total_venta',
                'v.estado',
                'c.nombre as nombre_cliente'
            )
            ->orderBy('v.fecha_hora', 'desc')
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
            '# Venta',
            'Fecha',
            'Folio',
            'Cliente',
            'Tipo Comprobante',
            'Total',
            'Estado',
        ];
    }

    /**
     * Mapea los datos para cada fila del Excel.
     *
     * @param mixed $venta
     * @return array
     */
    public function map($venta): array
    {
        // Se personaliza el orden y formato de cada fila
        return [
            $venta->idventa,
            $venta->fecha_hora,
            $venta->num_folio,
            $venta->nombre_cliente,
            $venta->tipo_comprobante,
            $venta->total_venta,
            $venta->estado,
        ];
    }
}
