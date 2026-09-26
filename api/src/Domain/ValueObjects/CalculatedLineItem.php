<?php

namespace Domain\ValueObjects;

/**
 * Class CalculatedLineItem
 *
 * Contiene el resultado financiero y los snapshots de producto listos para el detalle de la factura.
 *
 * @package Domain\ValueObjects
 */
readonly class CalculatedLineItem
{
    /**
     * @param int $productId ID del producto.
     * @param string $productName Snapshot histórico del nombre.
     * @param float $quantity Cantidad facturada.
     * @param float $unitPrice Precio unitario aplicado.
     * @param float $unitCost Snapshot del costo unitario actual.
     * @param float $taxRate Tasa de impuesto (ej. 0.15).
     * @param float $lineSubtotal Cantidad * Precio unitario.
     * @param float $discountPercentage Porcentaje de descuento aplicado.
     * @param float $lineDiscount Monto de descuento de la línea.
     * @param float $lineTaxAmount Monto de impuesto de la línea.
     * @param float $lineNetTotal Monto neto total cobrado en la línea.
     * @param float $exemptAmount Monto exento si aplica.
     */
    public function __construct(
        public int $productId,
        public string $productName,
        public float $quantity,
        public float $unitPrice,
        public float $unitCost,
        public float $taxRate,
        public float $lineSubtotal,
        public float $discountPercentage,
        public float $lineDiscount,
        public float $lineTaxAmount,
        public float $lineNetTotal,
        public float $exemptAmount = 0.0
    ) {}

    /**
     * Devuelve el mapeo exacto para la tabla sales_invoice_details del Hito 1.
     *
     * @return array<string, mixed>
     */
    public function toDatabaseArray(): array
    {
        return [
            'product_id'          => $this->productId,
            'product_name'        => $this->productName,
            'quantity'            => $this->quantity,
            'unit_price'          => $this->unitPrice,
            'unit_cost'           => $this->unitCost,
            'tax_rate'            => $this->taxRate,
            'line_subtotal'       => $this->lineSubtotal,
            'discount_percentage' => $this->discountPercentage,
            'line_discount'       => $this->lineDiscount,
            'line_tax_amount'     => $this->lineTaxAmount,
            'line_net_total'      => $this->lineNetTotal,
            'exempt_amount'       => $this->exemptAmount,
        ];
    }
}