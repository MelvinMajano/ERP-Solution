/**
 * Entidad de Dominio que representa a una empresa dentro del ERP Multi-tenant.
 */
export interface Tenant {
  tenant_id: number;
  company_name: string;
  subdomain: string;
}