<?php

declare(strict_types=1);

namespace Modules\Billing\Services;

use Domain\Contracts\ProductRepositoryInterface;
use Domain\Contracts\SaleInvoiceRepositoryInterface;
use Domain\Entities\SaleInvoice;
use Domain\Events\SaleCreatedEvent;
use Domain\Services\InvoiceFinancialEngine;
use Domain\Services\SaleStockDomainService;
use Illuminate\Database\Capsule\Manager as Capsule;
use Infrastructure\Base\BaseService;
use Modules\Billing\DTOs\CreateSaleDTO;

/**
 * Class CreateSaleService
 *
 * Orquesta la creación atómica de la factura en el módulo Billing.
 *
 * @package Modules\Billing\Services
 */
class CreateSaleService extends BaseService
{
    public function __construct(
        private readonly SaleInvoiceRepositoryInterface $saleInvoiceRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly SaleStockDomainService $stockDomainService,
        private readonly InvoiceFinancialEngine $financialEngine
    ) {}

    public function execute(CreateSaleDTO $dto): SaleInvoice
    {
        return Capsule::transaction(function () use ($dto) {
            // 1. Invariante de Stock en WMS
            $updatedProducts = $this->stockDomainService->validateAndReserveStock($dto->items);

            // 2. Procesamiento Financiero
            $summary = $this->financialEngine->process($dto->items);

            // 3. Creación de la factura usando la factoría de tu entidad SaleInvoice
            $invoiceNumber = 'FAC-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
            $saleInvoice = SaleInvoice::createFromDTO($dto, $summary, $invoiceNumber);
            
            $this->saleInvoiceRepository->save($saleInvoice);

            // 4. Inserción masiva de los detalles respetando las columnas de sales_invoice_details
            foreach ($summary->lines as $line) {
                $saleInvoice->details()->create($line->toDatabaseArray());
            }

            // 5. Actualización del stock físico en BD
            foreach ($updatedProducts as $product) {
                $this->productRepository->update($product->id, [
                    'current_stock' => $product->current_stock,
                ]);
            }

            // 6. Notificación de evento colateral
            event(new SaleCreatedEvent($saleInvoice, $dto->items));

            return $saleInvoice;
        });
    }
}