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

            $subtotal += $line->subtotal;
            $discountTotal += $line->discount;
            $taxableBaseTotal += $line->taxableBase;
            $taxTotal += $line->tax;
            $netTotal += $line->total;

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