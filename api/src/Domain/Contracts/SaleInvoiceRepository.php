<?php

namespace Domain\Contracts;

use Domain\Entities\SaleInvoice;

/**
 * Contrato de abstracción para la gestión de persistencia de Ventas y Facturación.
 */
interface SaleInvoiceRepositoryInterface
{
    /**
     * Registra en persistencia la cabecera y el detalle de una factura de venta.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): SaleInvoice;

    /**
     * Obtiene una factura de venta con sus relaciones de detalle precargadas.
     */
    public function findById(int $id): ?SaleInvoice;
}