-- =============================================================================
-- CAPA BASE SAAS & CONTROL DE ACCESO (CORE)
-- =============================================================================

-- 1. Empresas o Clientes del SaaS (Tenants)
CREATE TABLE tenants (
    id VARCHAR(36) PRIMARY KEY COMMENT 'Identificador único UUID de la empresa/inquilino (tenant).',
    company_name VARCHAR(150) NOT NULL COMMENT 'Nombre comercial o razón social de la empresa cliente del SaaS.',
    subdomain VARCHAR(50) UNIQUE NOT NULL COMMENT 'Subdominio asignado a la empresa para acceso (ej. empresa.saas.com).',
    status VARCHAR(20) NOT NULL DEFAULT 'active' COMMENT 'Estado del inquilino en la plataforma SaaS (ej. active, suspended, canceled).',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora de registro de la empresa en la plataforma.',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha y hora de la última actualización de datos de la empresa.'
);

-- 2. Catálogo Global de Módulos del ERP
CREATE TABLE modules (
    id VARCHAR(50) PRIMARY KEY COMMENT 'Clave primaria identificadora del módulo (ej. inventory, sales, purchases, cash).',
    name VARCHAR(100) NOT NULL COMMENT 'Nombre visible del módulo en la interfaz de usuario.',
    description TEXT NULL COMMENT 'Descripción detallada de las funcionalidades que abarca el módulo.',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Indica si el módulo está disponible globalmente en la plataforma.'
);

-- 3. Módulos Habilitados por Empresa
CREATE TABLE tenant_modules (
    tenant_id VARCHAR(36) NOT NULL COMMENT 'ID de la empresa/tenant a la que se le asigna el módulo.',
    module_id VARCHAR(50) NOT NULL COMMENT 'ID del módulo habilitado para la empresa.',
    is_enabled BOOLEAN DEFAULT TRUE COMMENT 'Estado de activación del módulo para la empresa específica.',
    expires_at TIMESTAMP NULL COMMENT 'Fecha de expiración o renovación de la suscripción al módulo.',
    PRIMARY KEY (tenant_id, module_id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);

-- 4. Permisos Globales del Sistema
CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único incremental del permiso.',
    module_id VARCHAR(50) NOT NULL COMMENT 'Módulo al que pertenece el permiso.',
    section VARCHAR(100) NOT NULL COMMENT 'Sección o recurso específico del sistema (ej. products, invoices, users).',
    action VARCHAR(100) NOT NULL COMMENT 'Acción concreta autorizada (ej. create, read, update, delete).',
    description TEXT NULL COMMENT 'Explicación del alcance y propósito del permiso.',
    level ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'low' COMMENT 'Nivel de criticidad del permiso para auditoría y seguridad.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora de creación del permiso.',
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    UNIQUE(module_id, section, action)
);

-- 5. Roles Personalizados por Empresa
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único incremental del rol.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la cual pertenece este rol personalizado.',
    name VARCHAR(100) NOT NULL COMMENT 'Nombre asignado al rol (ej. Administrador, Cajero, Inventariador).',
    description VARCHAR(255) NULL COMMENT 'Descripción de las funciones asignadas a este rol.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora de creación del rol.',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última modificación del rol.',
    created_by INT NULL COMMENT 'ID del usuario que creó el rol.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    UNIQUE(tenant_id, name)
);

-- 6. Asignación de Permisos a Roles
CREATE TABLE rol_permissions (
    rol_id INT NOT NULL COMMENT 'ID del rol al que se otorga el permiso.',
    permission_id INT NOT NULL COMMENT 'ID del permiso otorgado al rol.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora en que se asignó el permiso al rol.',
    PRIMARY KEY (rol_id, permission_id),
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

-- 7. Usuarios del Sistema
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único incremental del usuario.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa (tenant) a la que pertenece el usuario.',
    username VARCHAR(50) NOT NULL COMMENT 'Nombre de usuario único dentro de la empresa para iniciar sesión.',
    password VARCHAR(255) NOT NULL COMMENT 'Contraseña encriptada (hash) del usuario.',
    first_names VARCHAR(100) NOT NULL COMMENT 'Nombres del usuario.',
    last_names VARCHAR(100) NOT NULL COMMENT 'Apellidos del usuario.',
    email VARCHAR(150) NOT NULL COMMENT 'Correo electrónico único del usuario para notificaciones y acceso.',
    rol_id INT NOT NULL COMMENT 'Rol asignado que determina los permisos del usuario.',
    status VARCHAR(20) DEFAULT 'active' COMMENT 'Estado de la cuenta del usuario (ej. active, inactive, locked).',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora de registro del usuario.',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última actualización del usuario.',
    created_by INT NULL COMMENT 'ID del usuario creador de la cuenta.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (rol_id) REFERENCES roles(id),
    UNIQUE(tenant_id, username),
    UNIQUE(tenant_id, email)
);

-- =============================================================================
-- MÓDULO 1: INVENTARIO (PRODUCTOS FÍSICOS & KARDEX)
-- =============================================================================

-- 8. Proveedores
CREATE TABLE suppliers (
    id VARCHAR(36) PRIMARY KEY COMMENT 'Identificador único UUID del proveedor.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece este proveedor.',
    rtn VARCHAR(20) NULL COMMENT 'Registro Tributario Nacional del proveedor (Honduras - SAR).',
    name VARCHAR(150) NOT NULL COMMENT 'Nombre comercial o de fantasía del proveedor.',
    business_name VARCHAR(200) NULL COMMENT 'Razón social legal del proveedor registrada ante la SAR.',
    email VARCHAR(150) NULL COMMENT 'Correo electrónico de contacto comercial o pedidos.',
    phone VARCHAR(50) NULL COMMENT 'Número telefónico de contacto del proveedor.',
    address VARCHAR(255) NULL COMMENT 'Dirección física u oficina del proveedor.',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Indica si el proveedor está habilitado para compras.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora de registro del proveedor.',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última actualización del proveedor.',
    created_by INT NOT NULL COMMENT 'ID del usuario que registró al proveedor.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_suppliers_tenant (tenant_id, id)
);

-- 9. Catálogo de Productos
CREATE TABLE products (
    id VARCHAR(36) PRIMARY KEY COMMENT 'Identificador único UUID del producto.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece el producto.',
    sku VARCHAR(50) NOT NULL COMMENT 'Código único de control interno del producto por empresa.',
    barcode VARCHAR(100) NULL COMMENT 'Código de barras para escaneo físico en punto de venta (POS).',
    name VARCHAR(150) NOT NULL COMMENT 'Nombre o descripción comercial del producto.',
    primary_supplier_id VARCHAR(36) NULL COMMENT 'Proveedor principal o habitual para reabastecimiento.',
    price DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Precio de venta base al público antes de impuestos.',
    cost DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Costo unitario actual de adquisición del producto.',
    current_stock DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Existencia o stock físico actual consolidado.',
    is_service BOOLEAN DEFAULT FALSE COMMENT 'Flag que indica si es un servicio intangible (FALSE por defecto para Fase 1).',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Indica si el producto está disponible para venta/compra.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora de creación del producto.',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última modificación del producto.',
    created_by INT NOT NULL COMMENT 'ID del usuario que registró el producto.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (primary_supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id),
    UNIQUE (tenant_id, sku),
    INDEX idx_products_tenant (tenant_id, id),
    INDEX idx_products_barcode (tenant_id, barcode)
);

-- 10. Motivos de Movimiento de Inventario
CREATE TABLE movement_reasons (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único del motivo de movimiento.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece este motivo.',
    name VARCHAR(150) NOT NULL COMMENT 'Nombre descriptivo de la causa (ej. Venta POS, Compra directa, Ajuste por merma).',
    movement_type VARCHAR(10) NOT NULL COMMENT 'Tipo de efecto físico en stock: IN (Entrada) o OUT (Salida).',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Indica si el motivo está disponible para ser seleccionado.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación del motivo.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
);

-- 11. Kardex de Inventario (Auditoría Histórica de Stock)
CREATE TABLE inventory_movements (
    id VARCHAR(36) PRIMARY KEY COMMENT 'Identificador único UUID del movimiento de inventario.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece la transacción.',
    product_id VARCHAR(36) NOT NULL COMMENT 'Producto afectado por el movimiento.',
    movement_reason_id INT NOT NULL COMMENT 'ID de la causa o motivo registrado para este movimiento.',
    type VARCHAR(10) NOT NULL COMMENT 'Dirección del movimiento: IN (Entrada) u OUT (Salida).',
    quantity DECIMAL(12, 4) NOT NULL COMMENT 'Cantidad física de unidades que ingresaron o salieron.',
    previous_stock DECIMAL(12, 4) NOT NULL COMMENT 'Stock o existencia exacta del producto antes de ejecutar el movimiento.',
    new_stock DECIMAL(12, 4) NOT NULL COMMENT 'Stock o existencia posterior al movimiento (Previous +/- Quantity).',
    unit_cost DECIMAL(12, 4) NOT NULL COMMENT 'Costo unitario del producto al momento exacto del movimiento.',
    reference_type VARCHAR(50) NOT NULL COMMENT 'Origen de la transacción (ej. SALE, PURCHASE, MANUAL_ADJUSTMENT).',
    reference_id VARCHAR(36) NULL COMMENT 'ID del documento de origen (ID de factura de venta, ID de compra, etc.).',
    notes VARCHAR(255) NULL COMMENT 'Observaciones o notas adicionales aclaratorias sobre el movimiento.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora exacta en que se registró el movimiento.',
    created_by INT NOT NULL COMMENT 'ID del usuario que ejecutó o registró el movimiento.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (movement_reason_id) REFERENCES movement_reasons(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_kardex_tenant_product (tenant_id, product_id)
);

-- =============================================================================
-- MÓDULO 2: CLIENTES, VENTAS Y FACTURACIÓN DIRECTA
-- =============================================================================

-- 12. Clientes
CREATE TABLE customers (
    id VARCHAR(36) PRIMARY KEY COMMENT 'Identificador único UUID del cliente.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece el cliente.',
    rtn VARCHAR(20) NULL COMMENT 'Registro Tributario Nacional para facturación a personas jurídicas/comerciales.',
    dni VARCHAR(20) NULL COMMENT 'Documento Nacional de Identificación para cliente persona natural.',
    name VARCHAR(150) NOT NULL COMMENT 'Nombre completo o nombre comercial del cliente.',
    business_name VARCHAR(200) NULL COMMENT 'Razón social registrada ante la SAR (si aplica).',
    email VARCHAR(150) NULL COMMENT 'Correo electrónico para envío de facturas digitales.',
    phone VARCHAR(50) NULL COMMENT 'Número de teléfono de contacto.',
    address VARCHAR(255) NULL COMMENT 'Dirección de entrega o domicilio fiscal.',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Indica si el cliente está activo para realizar ventas.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación del expediente del cliente.',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última actualización del cliente.',
    created_by INT NOT NULL COMMENT 'ID del usuario que registró al cliente.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_customers_tenant (tenant_id, id)
);

-- 13. Cabecera de Ventas / Facturas
CREATE TABLE sales_invoices (
    id VARCHAR(36) PRIMARY KEY COMMENT 'Identificador único UUID de la factura de venta.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece la venta.',
    customer_id VARCHAR(36) NOT NULL COMMENT 'Cliente al que se le emite la factura.',
    invoice_number VARCHAR(50) NOT NULL COMMENT 'Número correlativo o número fiscal de la factura de venta.',
    cashier_user_id INT NOT NULL COMMENT 'ID del cajero/usuario que procesó la venta en caja.',
    
    -- Campos reservados para expansión futura (CAI / Exenciones SAR)
    cai_id VARCHAR(36) NULL COMMENT 'Campo reservado: ID del rango CAI asignado por la SAR.',
    cash_batch_id VARCHAR(36) NULL COMMENT 'ID del turno de caja abierto en el que se realizó la venta.',
    exempt_order_number VARCHAR(100) NULL COMMENT 'Campo reservado: Número de orden de compra exenta (normativa SAR).',
    exempt_certificate_number VARCHAR(100) NULL COMMENT 'Campo reservado: Constancia de registro de exonerado (normativa SAR).',
    exempt_sag_number VARCHAR(100) NULL COMMENT 'Campo reservado: Registro SAG para exención agrícola (normativa SAR).',
    
    -- Totales de la venta
    subtotal DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Suma del subtotal de las líneas antes de descuentos e impuestos.',
    discount_total DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Monto total descontado en la factura.',
    tax_total DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Monto total cobrado por concepto de ISV (Impuesto sobre Ventas).',
    net_total DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Monto final neto a pagar por el cliente (Subtotal - Desc + Impuestos).',
    
    status VARCHAR(20) NOT NULL DEFAULT 'ISSUED' COMMENT 'Estado de la factura (ej. ISSUED, VOIDED, PENDING).',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora exacta de emisión de la factura.',
    created_by INT NOT NULL COMMENT 'ID del usuario que registró la factura.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (cashier_user_id) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    UNIQUE (tenant_id, invoice_number),
    INDEX idx_sales_tenant (tenant_id, id)
);

-- 14. Detalle de Ventas
CREATE TABLE sales_invoice_details (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único incremental del detalle de venta.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece este detalle.',
    invoice_id VARCHAR(36) NOT NULL COMMENT 'ID de la factura cabecera a la que pertenece esta línea.',
    product_id VARCHAR(36) NOT NULL COMMENT 'ID del producto vendido.',
    product_name VARCHAR(150) NOT NULL COMMENT 'Snapshot o copia textual del nombre del producto al momento de facturar.',
    quantity DECIMAL(12, 4) NOT NULL COMMENT 'Cantidad de unidades vendidas.',
    unit_price DECIMAL(12, 4) NOT NULL COMMENT 'Precio unitario de venta aplicado en esta línea.',
    unit_cost DECIMAL(12, 4) NOT NULL COMMENT 'Snapshot del costo unitario del producto al venderlo (para margen bruto).',
    tax_rate DECIMAL(5, 2) NOT NULL DEFAULT 15.00 COMMENT 'Porcentaje de impuesto aplicable (ej. 15.00 para ISV general, 18.00 o 0.00).',
    
    line_subtotal DECIMAL(12, 4) NOT NULL COMMENT 'Subtotal bruto de la línea (Quantity * Unit Price).',
    discount_percentage DECIMAL(5, 2) NOT NULL DEFAULT 0.00 COMMENT 'Porcentaje de descuento otorgado en esta línea.',
    line_discount DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Monto monetario del descuento otorgado en la línea.',
    line_tax_amount DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Monto monetario de impuesto calculado para la línea.',
    line_net_total DECIMAL(12, 4) NOT NULL COMMENT 'Monto total neto cobrado por esta línea (Subtotal - Desc + Tax).',
    exempt_amount DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Monto exento o exonerado de impuesto en la línea.',
    
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (invoice_id) REFERENCES sales_invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- =============================================================================
-- MÓDULO 3: COMPRAS DIRECTAS A PROVEEDORES
-- =============================================================================

-- 15. Compras Directas
CREATE TABLE purchases (
    id VARCHAR(36) PRIMARY KEY COMMENT 'Identificador único UUID de la compra directa.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece el registro de compra.',
    supplier_id VARCHAR(36) NOT NULL COMMENT 'Proveedor al que se le realizó la compra.',
    supplier_invoice_number VARCHAR(50) NOT NULL COMMENT 'Número de factura o documento físico emitido por el proveedor.',
    subtotal DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Suma de importes brutos de la compra antes de impuestos.',
    tax_total DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Monto total del impuesto de venta pagado en la compra.',
    total_amount DECIMAL(12, 4) NOT NULL DEFAULT 0.0000 COMMENT 'Monto total final pagado por la compra.',
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha fiscal o de emisión impresa en la factura del proveedor.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora de registro de la compra en el sistema.',
    created_by INT NOT NULL COMMENT 'ID del usuario que registró la compra en el sistema.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_purchases_tenant (tenant_id, id)
);

-- 16. Detalle de Compras Directas
CREATE TABLE purchase_details (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único incremental del detalle de compra.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece esta línea de compra.',
    purchase_id VARCHAR(36) NOT NULL COMMENT 'ID de la compra cabecera a la que pertenece esta línea.',
    product_id VARCHAR(36) NOT NULL COMMENT 'Producto reabastecido en la compra.',
    quantity DECIMAL(12, 4) NOT NULL COMMENT 'Cantidad de unidades compradas/ingresadas.',
    unit_cost DECIMAL(12, 4) NOT NULL COMMENT 'Costo unitario de adquisición negociado con el proveedor.',
    tax_rate DECIMAL(5, 2) NOT NULL DEFAULT 15.00 COMMENT 'Porcentaje de impuesto soportado en la compra (ej. 15.00).',
    line_subtotal DECIMAL(12, 4) NOT NULL COMMENT 'Subtotal de la línea (Quantity * Unit Cost).',
    line_tax DECIMAL(12, 4) NOT NULL COMMENT 'Monto de impuesto pagado en esta línea.',
    line_total DECIMAL(12, 4) NOT NULL COMMENT 'Importe total de la línea (Subtotal + Tax).',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- =============================================================================
-- MÓDULO 4: CONTROL DE CAJA Y ARQUEO FÍSICO
-- =============================================================================

-- 17. Turnos / Apertura y Cierre de Caja
CREATE TABLE cash_batches (
    id VARCHAR(36) PRIMARY KEY COMMENT 'Identificador único UUID de la sesión/turno de caja.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece el turno de caja.',
    cashier_user_id INT NOT NULL COMMENT 'Usuario/Cajero responsable de la caja durante este turno.',
    opening_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora exactas de apertura de la caja.',
    closing_date TIMESTAMP NULL COMMENT 'Fecha y hora exactas de cierre del turno de caja.',
    opening_balance DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'Monto inicial o cambio entregado en efectivo al cajero al abrir.',
    expected_amount DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'Monto total en efectivo calculado teóricamente por el sistema al cerrar.',
    actual_amount DECIMAL(12, 2) NULL DEFAULT 0.00 COMMENT 'Monto total en efectivo contado físicamente por el cajero en el arqueo.',
    difference DECIMAL(12, 2) NULL DEFAULT 0.00 COMMENT 'Diferencia resultante del arqueo: Faltante (-) o Sobrante (+) (Actual - Expected).',
    total_sales DECIMAL(12, 2) DEFAULT 0.00 COMMENT 'Monto acumulado de ventas realizadas en efectivo durante el turno.',
    status VARCHAR(20) NOT NULL DEFAULT 'OPEN' COMMENT 'Estado de la caja (ej. OPEN, CLOSED).',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro de la apertura.',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última actualización del estado de caja.',
    created_by INT NOT NULL COMMENT 'ID del usuario que abrió el turno de caja.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (cashier_user_id) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_cash_batches_tenant (tenant_id, id)
);

-- 18. Arqueo por Denominación de Billetes/Monedas (Honduras HNL)
CREATE TABLE cash_batch_details (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único incremental del desglose de dinero.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece el desglose.',
    cash_batch_id VARCHAR(36) NOT NULL COMMENT 'ID del turno de caja al que corresponde este conteo físico.',
    bill_value ENUM(
        '0.05', '0.10', '0.20', '0.50',
        '1', '2', '5', '10', '20', '50', '100', '200', '500'
    ) NOT NULL COMMENT 'Denominación oficial de billetes y monedas de Honduras (Lempiras - HNL).',
    quantity INT NOT NULL DEFAULT 0 COMMENT 'Cantidad de piezas/billetes/monedas contadas físicamente de esta denominación.',
    total_amount DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'Monto monetario total resultante (Bill Value * Quantity).',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro del conteo.',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última modificación del conteo.',
    created_by INT NOT NULL COMMENT 'ID del usuario que realizó el arqueo físico.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (cash_batch_id) REFERENCES cash_batches(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- 19. Movimientos Directos de Dinero en Caja
CREATE TABLE cash_movements (
    id VARCHAR(36) PRIMARY KEY COMMENT 'Identificador único UUID del movimiento manual o automático de caja.',
    tenant_id VARCHAR(36) NOT NULL COMMENT 'Empresa a la que pertenece el movimiento.',
    cash_batch_id VARCHAR(36) NOT NULL COMMENT 'Turno de caja afectado por la entrada/salida de dinero.',
    type VARCHAR(10) NOT NULL COMMENT 'Tipo de flujo monetario: INCOME (Entrada de dinero) o EXPENSE (Salida/Aporte).',
    amount DECIMAL(12, 4) NOT NULL COMMENT 'Monto exacto de dinero ingresado o retirado de la caja.',
    reference_type VARCHAR(50) NOT NULL COMMENT 'Origen del flujo de efectivo (ej. SALE, PURCHASE, MANUAL_ADJUSTMENT, PETTY_CASH).',
    reference_id VARCHAR(36) NULL COMMENT 'ID del documento de soporte vinculado (ID de venta, ID de compra, etc.).',
    description VARCHAR(255) NULL COMMENT 'Justificación o concepto del movimiento en caja.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora exacta del movimiento.',
    created_by INT NOT NULL COMMENT 'ID del usuario autor/responsable de la transacción.',
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (cash_batch_id) REFERENCES cash_batches(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_cash_movements_tenant (tenant_id, cash_batch_id)
);