import type { Tenant } from "@domain/entities/Tenant";
import type { User } from "@domain/entities/User";


/** Respuesta del endpoint /auth/check-email */
export interface CheckEmailData {
  pre_auth_token: string;
  tenants: Tenant[];
}

/** Respuesta del endpoint /auth/login-password */
export interface LoginPasswordData {
  access_token: string;
  token_type: string;
  expires_in: number;
  user: User;
}

/** Payload para registrar un nuevo Tenant */
export interface RegisterTenantPayload {
  company_name: string;
  subdomain: string;
  first_names: string;
  last_names: string;
  email: string;
  password: string;
}

/**
 * Contrato de estado global de autenticación reutilizable en hooks y vistas.
 */
export interface AuthState {
  preAuthToken: string | null;
  sessionToken: string | null;
  tenantId: number | null;
  user: User | null;
  tenants: Tenant[];

  setPreAuthData: (token: string, tenants: Tenant[]) => void;
  setSessionData: (token: string, user: User, tenantId?: number) => void;
  setSelectedTenantId: (tenantId: number) => void;
  logout: () => void;
}