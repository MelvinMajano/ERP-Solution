<?php

namespace Domain\Contracts;

use Domain\Entities\SaleInvoice;

/**
 * Contrato de abstracción para la gestión de persistencia de Ventas y Facturación.
 */
interface SaleInvoiceRepositoryInterface
{
    /**
     * Persiste o actualiza en la capa de almacenamiento la entidad de una factura de venta.
     *
     * @param SaleInvoice $saleInvoice Entidad de dominio de la factura a guardar.
     * @return SaleInvoice
     */
    public function save(SaleInvoice $saleInvoice): SaleInvoice;
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