/**
 * Excepción base para errores técnicos, peticiones HTTP fallidas e infraestructura.
 */
export class InfrastructureException extends Error {
  /**
   * Código de estado HTTP retornado por la API Slim 4 (si aplica).
   */
  public statusCode?: number;

  constructor(message: string, statusCode?: number) {
    super(message);
    this.name = 'InfrastructureException';
    this.statusCode = statusCode;
  }
}

/**
 * Se dispara cuando la petición requiere autenticación válida o el token JWT ha expirado (401/403).
 */
export class UnauthorizedException extends InfrastructureException {
  constructor(message = 'Sesión expirada o no autorizada.') {
    super(message, 401);
    this.name = 'UnauthorizedException';
  }
}

/**
 * Se dispara cuando no es posible establecer comunicación con el servidor (Offline / Cors / Timeout).
 */
export class NetworkException extends InfrastructureException {
  constructor(message = 'Error de conexión con el servidor.') {
    super(message, 500);
    this.name = 'NetworkException';
  }
}

/**
 * Se dispara cuando la ruta o recurso solicitado no existe en la API (404).
 */
export class NotFoundException extends InfrastructureException {
  constructor(message = 'El recurso solicitado no fue encontrado.') {
    super(message, 404);
    this.name = 'NotFoundException';
  }
}