/**
 * Entidad de Dominio que representa al usuario autenticado.
 */
export interface User {
  id: number;
  username: string;
  email: string;
  tenant_id: number;
}