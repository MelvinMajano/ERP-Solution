<?php

namespace Modules\Billing\Repositories;

use Domain\Contracts\SaleInvoiceRepositoryInterface;
use Domain\Entities\SaleInvoice;
use Infrastructure\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Repositorio de Infraestructura para la gestión de Facturas de Venta.
 */
class SaleInvoiceRepository extends BaseRepository implements SaleInvoiceRepositoryInterface
{
    /**
     * Columnas permitidas para ordenamiento dinámico en consultas paginadas.
     *
     * @var array<int, string>
     */
    protected array $sortableColumns = [
        'id',
        'invoice_number',
        'customer_id',
        'total_amount',
        'net_total',
        'status',
        'issue_date',
        'created_at',
    ];

    /**
     * Columnas habilitadas para búsquedas por coincidencia parcial (LIKE).
     *
     * @var array<int, string>
     */
    protected array $likeColumns = [
        'invoice_number',
        'notes',
    ];

    /**
     * Crea una nueva instancia del repositorio de facturas.
     *
     * @param SaleInvoice $model Modelo Eloquent de la factura de venta.
     */
    public function __construct(SaleInvoice $model)
    {
        parent::__construct($model);
    }

    /**
     * Crea y persiste un nuevo registro en la base de datos a partir de un arreglo.
     * Sobreescrito para resolver la incompatibilidad de firma con el contrato del dominio.
     *
     * @param array $attributes Datos del registro a crear.
     * @return SaleInvoice
     */
    public function create(array $attributes): SaleInvoice
    {
        /** @var SaleInvoice */
        return parent::create($attributes);
    }

    /**
     * Busca una factura de venta por su ID primario.
     *
     * @param int|string $id Identificador único de la factura.
     * @return SaleInvoice|null
     */
    public function findById(int|string $id): ?SaleInvoice
    {
        /** @var SaleInvoice|null */
        return parent::findById($id);
    }

    /**
     * Persiste o actualiza los cambios de una entidad SaleInvoice.
     *
     * @param SaleInvoice $invoice Entidad de la factura a guardar.
     * @return SaleInvoice
     */
    public function save(SaleInvoice $invoice): SaleInvoice
    {
        $invoice->save();

        return $invoice;
    }

    /**
     * Busca una factura por su número correlativo dentro del tenant activo.
     *
     * @param string $invoiceNumber Número correlativo de la factura (ej: FAC-000001).
     * @return SaleInvoice|null
     */
    public function findByInvoiceNumber(string $invoiceNumber): ?SaleInvoice
    {
        /** @var SaleInvoice|null */
        return $this->query()
            ->where('invoice_number', $invoiceNumber)
            ->first();
    }
}