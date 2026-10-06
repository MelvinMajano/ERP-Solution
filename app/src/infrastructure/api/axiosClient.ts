import axios, { AxiosError } from 'axios';
import { useAuthStore } from '@modules/core/store/useAuthStore';
import { ErrorHandler } from '@infrastructure/exceptions/ErrorHandler';

/**
 * Cliente HTTP base para la comunicación con la API Slim 4.
 * 
 * Gestiona de forma reactiva la inyección de encabezados de sesión (`Authorization: Bearer`),
 * la identificación Multi-Tenant (`X-Tenant-ID`) y delega el procesamiento de fallos
 * al `ErrorHandler`.
 */
export const axiosClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL || import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

/**
 * Interceptor de Peticiones:
 * Extrae tokens dinámicamente desde el estado global de Zustand para evitar desincronización
 * de credenciales durante el flujo de login en 2 pasos.
 */
axiosClient.interceptors.request.use((config) => {
  const { sessionToken, preAuthToken, tenantId } = useAuthStore.getState();
  const token = sessionToken || preAuthToken;

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  if (tenantId) {
    config.headers['X-Tenant-ID'] = tenantId;
  }

  return config;
});

/**
 * Interceptor de Respuestas:
 * Mapea y procesa cualquier rechazo HTTP delegando la responsabilidad a `ErrorHandler`.
 */
axiosClient.interceptors.response.use(
  (response) => response,
  (error: AxiosError<any>) => {
    const exception = ErrorHandler.fromAxios(error);
    ErrorHandler.handle(exception);
    return Promise.reject(exception);
  }
);