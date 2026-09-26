<?php

namespace Domain\DomainServices;

use Domain\Contracts\CalculationRuleInterface;
use Domain\ValueObjects\InvoiceCalculationSummary;
use Domain\ValueObjects\SaleItemInput;

class InvoiceFinancialEngine
{
    public function __construct(
        private readonly CalculationRuleInterface $rule
    ) {}

    /**
     * @param array<int, SaleItemInput> $items
     */
    public function process(array $items): InvoiceCalculationSummary
    {
        $subtotal = 0.0;
        $discountTotal = 0.0;
        $taxableBaseTotal = 0.0;
        $taxTotal = 0.0;
        $netTotal = 0.0;
        $processedLines = [];

        foreach ($items as $item) {
            $line = $this->rule->processLine($item);

            // Mapeo corregido a las propiedades de CalculatedLineItem
            $subtotal += $line->lineSubtotal;
            $discountTotal += $line->lineDiscount;
            
            // La base imponible es la diferencia entre el subtotal de línea y su descuento
            $taxableBaseTotal += ($line->lineSubtotal - $line->lineDiscount);
            
            $taxTotal += $line->lineTaxAmount;
            $netTotal += $line->lineNetTotal;

            $processedLines[] = $line;
        }

        return new InvoiceCalculationSummary(
            subtotal: round($subtotal, 2),
            discountTotal: round($discountTotal, 2),
            taxableBaseTotal: round($taxableBaseTotal, 2),
            taxTotal: round($taxTotal, 2),
            netTotal: round($netTotal, 2),
            lines: $processedLines
        );
    }
}