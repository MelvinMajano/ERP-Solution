/**
 * Estructura de respuesta unificada para todas las peticiones a la API Slim 4.
 */
export interface ApiResponse<T> {
  status: 'success' | 'error';
  message: string;
  data: T;
  errors: any[];
}