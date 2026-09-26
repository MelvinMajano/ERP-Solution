<?php

namespace Modules\POS\Services;

use Domain\Contracts\ProductRepositoryInterface;
use Domain\Contracts\SaleInvoiceRepositoryInterface;
use Domain\Entities\SaleInvoice;
use Domain\Events\SaleCreatedEvent;
use Domain\DomainServices\SaleStockDomainService;
use Illuminate\Database\Capsule\Manager as Capsule;
use Infrastructure\Base\BaseService;
use Modules\Billing\DTOs\CreateSaleDTO;

class CreateSaleService extends BaseService
{
    public function __construct(
        private readonly SaleInvoiceRepositoryInterface $saleInvoiceRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly SaleStockDomainService $saleStockDomainService // Inyección del Domain Service
    ) {}

    public function execute(CreateSaleDTO $dto): SaleInvoice
    {
        return Capsule::transaction(function () use ($dto) {
            // 1. Delegamos la lógica cruzada de validación y reserva al Domain Service
            $updatedProducts = $this->saleStockDomainService->validateAndReserveStock($dto->items);

            // 2. Calculamos importes y preparamos los detalles
            $subtotal = 0.0;
            $discountTotal = 0.0;
            $preparedDetails = [];

            foreach ($dto->items as $item) {
                $lineSubtotal = $item['quantity'] * $item['unit_price'];
                $lineDiscount = $item['discount'];

                $subtotal += $lineSubtotal;
                $discountTotal += $lineDiscount;

                $preparedDetails[] = [
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount'   => $lineDiscount,
                    'subtotal'   => $lineSubtotal - $lineDiscount,
                ];
            }

            // 3. Persistimos la Factura
            $saleInvoice = $this->saleInvoiceRepository->create([
                'customer_id'     => $dto->customerId,
                'cashier_user_id' => $dto->cashierUserId,
                'cash_batch_id'   => $dto->cashBatchId,
                'invoice_number'  => 'POS-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT),
                'subtotal'        => $subtotal,
                'discount_total'  => $discountTotal,
                'tax_total'       => 0.0,
                'net_total'       => $subtotal - $discountTotal,
                'status'          => 'ISSUED',
                'notes'           => $dto->notes,
                'created_by'      => $dto->cashierUserId,
            ]);

            foreach ($preparedDetails as $detail) {
                $saleInvoice->details()->create($detail);
            }

            // 4. Persistimos los cambios de stock devueltos por el Domain Service
            foreach ($updatedProducts as $product) {
                $this->productRepository->update($product->id, [
                    'current_stock' => $product->current_stock,
                ]);
            }

            // 5. Publicamos el evento para los Observers (Kardex, Caja)
            event(new SaleCreatedEvent($saleInvoice, $dto->items));

            return $saleInvoice;
        });
    }
}