<?php

namespace Domain\Contracts;

use Domain\ValueObjects\CalculatedLineItem;
use Domain\ValueObjects\SaleItemInput;

/**
 * Interface CalculationRuleInterface
 *
 * Define el contrato para las estrategias de cálculo financiero por línea de detalle.
 *
 * @package Domain\Contracts
 */
interface CalculationRuleInterface
{
    /**
     * Procesa los importes e impuestos aplicables a un ítem individual de la venta.
     *
     * @param SaleItemInput $item Objeto inmutable con los datos de entrada del producto.
     * @return CalculatedLineItem Objeto inmutable con los importes calculados.
     */
    public function processLine(SaleItemInput $item): CalculatedLineItem;
}