<?php

namespace Domain\ValueObjects;

/**
 * Class SaleItemInput
 *
 * Value Object inmutable que representa los datos de entrada de un ítem para su procesamiento financiero.
 *
 * @package Domain\ValueObjects
 */
readonly class SaleItemInput
{
    /**
     * @param int $productId Identificador único del producto.
     * @param string $productName Nombre/descripción del producto para el snapshot.
     * @param float $quantity Cantidad a vender.
     * @param float $unitPrice Precio unitario de venta.
     * @param float $unitCost Costo unitario actual para el snapshot del Hito 1.
     * @param float $discountAmount Monto de descuento directo aplicado a la línea.
     * @param float $taxRate Tasa de impuesto aplicable (ej. 0.15 para ISV 15%).
     */
    public function __construct(
        public int $productId,
        public string $productName,
        public float $quantity,
        public float $unitPrice,
        public float $unitCost = 0.0,
        public float $discountAmount = 0.0,
        public float $taxRate = 0.15
    ) {}
}