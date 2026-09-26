<?php

namespace Domain\DomainServices\CalculationRules;

use Domain\Contracts\CalculationRuleInterface;
use Domain\ValueObjects\CalculatedLineItem;
use Domain\ValueObjects\SaleItemInput;

class StandardTaxCalculationRule implements CalculationRuleInterface
{
    /**
     * Realiza los cálculos financieros y asigna los datos de snapshot al detalle.
     */
    public function processLine(SaleItemInput $item): CalculatedLineItem
    {
        $lineSubtotal = round($item->quantity * $item->unitPrice, 2);
        
        // Cálculo de porcentaje o monto de descuento
        $lineDiscount = round($item->discountAmount, 2);
        $discountPercentage = $lineSubtotal > 0 ? round(($lineDiscount / $lineSubtotal) * 100, 2) : 0.0;
        
        $taxableBase = round(max(0.0, $lineSubtotal - $lineDiscount), 2);
        $lineTaxAmount = round($taxableBase * $item->taxRate, 2);
        $lineNetTotal = round($taxableBase + $lineTaxAmount, 2);

        return new CalculatedLineItem(
            productId: $item->productId,
            productName: $item->productName, // Snapshot del nombre
            quantity: $item->quantity,
            unitPrice: $item->unitPrice,
            unitCost: $item->unitCost,       // Snapshot del costo
            taxRate: $item->taxRate,
            lineSubtotal: $lineSubtotal,
            discountPercentage: $discountPercentage,
            lineDiscount: $lineDiscount,
            lineTaxAmount: $lineTaxAmount,
            lineNetTotal: $lineNetTotal,
            exemptAmount: 0.0
        );
    }
}