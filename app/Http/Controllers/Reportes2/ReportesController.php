<?php

namespace App\Http\Controllers\Reportes2;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

// !!! --- IMPORTS AÑADIDOS --- !!!
use App\Exports\VentasPorClienteExport; // Para la clase de exportación
use Maatwebsite\Excel\Facades\Excel;     // Para la fachada de Excel

class ReportesController extends Controller
{
    /**
     * Muestra el reporte de ventas por cliente en la web.
     *
     * @return \Illuminate\View\View
     */
    public function ventasPorCliente(): View
    {
        $clientesConTotalCompras = Cliente::query()
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

        return view('reportes2.ventas_por_cliente', [
            'clientesReporte' => $clientesConTotalCompras
        ]);
    }

    /**
     * Exporta el reporte de ventas por cliente a un archivo Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarVentasPorCliente()
    {
        // Esta línea ahora funcionará correctamente
        return Excel::download(new VentasPorClienteExport, 'reporte-ventas-por-cliente.xlsx');
    }
}
