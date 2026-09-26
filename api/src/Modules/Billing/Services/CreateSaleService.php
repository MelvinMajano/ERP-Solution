<?php

namespace Modules\Billing\Services;

use Domain\Contracts\EventDispatcherInterface;
use Domain\Contracts\ProductRepositoryInterface;
use Domain\Contracts\SaleInvoiceRepositoryInterface;
use Domain\DomainServices\InvoiceFinancialEngine;
use Domain\DomainServices\SaleStockDomainService;
use Domain\Entities\SaleInvoice;
use Domain\Events\SaleCreatedEvent;
use Illuminate\Database\Capsule\Manager as Capsule;
use Infrastructure\Base\BaseService;
use Modules\Billing\DTOs\CreateSaleDTO;
use Modules\Billing\Factories\SaleInvoiceFactory;

/**
 * Class CreateSaleService
 *
 * Servicio de aplicación encargada de orquestar la transacción atómica de creación de factura.
 *
 * @package Modules\Billing\Services
 */
class CreateSaleService extends BaseService
{
    public function __construct(
        private readonly SaleInvoiceRepositoryInterface $saleInvoiceRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly SaleStockDomainService $stockDomainService,
        private readonly InvoiceFinancialEngine $financialEngine,
        private readonly SaleInvoiceFactory $invoiceFactory,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * Ejecuta la creación atómica de la factura de venta.
     *
     * @param CreateSaleDTO $dto
     * @return SaleInvoice
     */
    public function execute(CreateSaleDTO $dto): SaleInvoice
    {
        return Capsule::transaction(function () use ($dto) {
            // 1. Validación de Invariante de Stock en WMS
            $updatedProducts = $this->stockDomainService->validateAndReserveStock($dto->items);

            // 2. Procesamiento Financiero (Motor extensible)
            $summary = $this->financialEngine->process($dto->items);

            // 3. Ensamblado limpio mediante la Factoría dedicada (cumpliendo SRP)
            $invoiceNumber = 'FAC-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
            $saleInvoice = $this->invoiceFactory->createFromDTO($dto, $summary, $invoiceNumber);

            // 4. Persistencia de la cabecera mediante la interfaz del repositorio
            $this->saleInvoiceRepository->save($saleInvoice);

            // 5. Asentamiento del detalle histórico (snapshots)
            foreach ($summary->lines as $line) {
                $saleInvoice->details()->create($line->toDatabaseArray());
            }

            // 6. Actualización física de inventario en base de datos
            foreach ($updatedProducts as $product) {
                $this->productRepository->update($product->id, [
                    'current_stock' => $product->current_stock,
                ]);
            }

            // 7. Emisión del evento de dominio desacoplado
            $this->eventDispatcher->dispatch(new SaleCreatedEvent($saleInvoice, $dto->items));

            return $saleInvoice;
        });
    }
}