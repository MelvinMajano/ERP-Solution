<?php

namespace Domain\ValueObjects;

/**
 * Class InvoiceCalculationSummary
 *
 * Value Object inmutable que condensa los agregados globales de la factura de venta.
 *
 * @package Domain\ValueObjects
 */
readonly class InvoiceCalculationSummary
{
    /**
     * @param float $subtotal Sumatoria de subtotales brutos de las líneas.
     * @param float $discountTotal Sumatoria global de descuentos aplicados.
     * @param float $taxableBaseTotal Sumatoria de bases imponibles sujetas a impuesto.
     * @param float $taxTotal Sumatoria del impuesto retenido/cobrado (ISV).
     * @param float $netTotal Total neto a pagar por la factura.
     * @param array<int, CalculatedLineItem> $lines Colección de líneas de detalle procesadas.
     */
    public function __construct(
        public float $subtotal,
        public float $discountTotal,
        public float $taxableBaseTotal,
        public float $taxTotal,
        public float $netTotal,
        public array $lines
    ) {}
}