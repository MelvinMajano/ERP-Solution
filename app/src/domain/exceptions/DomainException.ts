/**
 * Excepción base para todas las violaciones de reglas de negocio en el cliente.
 * 
 * Se utiliza para diferenciar errores lógicos del dominio o validaciones de formulario
 * de fallos en la infraestructura de red o respuestas HTTP del servidor.
 */
export class DomainException extends Error {
  constructor(message: string) {
    super(message);
    this.name = 'DomainException';
  }
}

/**
 * Representa un fallo en las reglas de validación de datos enviados o procesados.
 * 
 * Es capaz de almacenar el diccionario de errores por campo devuelto por la API (código 422)
 * o generado localmente por esquemas de validación (Zod).
 */
export class ValidationException extends DomainException {
  /**
   * Mapa de errores donde la clave es el campo y el valor la lista de mensajes de error.
   */
  public errors: Record<string, string[]>;

  /**
   * @param message Mensaje general de fallo de validación.
   * @param errors Diccionario opcional de errores por campo.
   */
  constructor(message: string, errors: Record<string, string[]> = {}) {
    super(message);
    this.name = 'ValidationException';
    this.errors = errors;
  }
}