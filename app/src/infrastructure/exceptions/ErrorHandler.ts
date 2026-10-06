import { AxiosError } from 'axios';
import { toast } from 'sonner';
import { useAuthStore } from '@modules/core/store/useAuthStore';
import { DomainException, ValidationException } from '@domain/exceptions/DomainException';
import {
  InfrastructureException,
  UnauthorizedException,
  NetworkException,
  NotFoundException,
} from './InfrastructureException';

/**
 * Manejador global de errores del Frontend.
 * 
 * Actúa como punto centralizado para traducir errores de red (Axios) a Excepciones de Dominio
 * e Infraestructura, desplegar alertas visuales (Toasts) y ejecutar efectos secundarios 
 * globales (como la purga de sesión en caso de error 401).
 */
export class ErrorHandler {
  /**
   * Transforma una falla capturada por Axios en una instancia de la jerarquía
   * de Excepciones del sistema, utilizando un mapa de estados HTTP.
   * 
   * @param error Objeto de error bruto producido por Axios.
   * @returns Instancia tipada de `DomainException` o `InfrastructureException`.
   */
  public static fromAxios(error: AxiosError<any>): Error {
    if (!error.response) {
      return new NetworkException();
    }

    const { status, data } = error.response;
    const message = data?.message || 'Error en la petición';
    const errors = data?.errors || [];

    const exceptionMap: Record<number, () => Error> = {
      401: () => new UnauthorizedException(message),
      403: () => new UnauthorizedException(message),
      404: () => new NotFoundException(message),
      422: () => new ValidationException(message, errors),
    };

    return exceptionMap[status]
      ? exceptionMap[status]()
      : new InfrastructureException(message, status);
  }

  /**
   * Evalúa la excepción recibida y notifica al usuario según su tipo.
   * 
   * - `ValidationException`: Muestra notificación de error y el primer detalle de campo.
   * - `UnauthorizedException`: Muestra alerta de sesión y purga el `useAuthStore`.
   * - Fallbacks: Captura excepciones desconocidas sin romper el renderizado del componente.
   * 
   * @param error Excepción de cualquier tipo lanzada en la aplicación.
   */
  public static handle(error: unknown): void {
    if (error instanceof ValidationException) {
      toast.error(error.message);
      
      const firstField = Object.keys(error.errors)[0];
      if (firstField && error.errors[firstField]?.[0]) {
        toast.warning(error.errors[firstField][0]);
      }
      return;
    }

    if (error instanceof DomainException) {
      toast.error(error.message);
      return;
    }

    if (error instanceof UnauthorizedException) {
      toast.error(error.message);
      useAuthStore.getState().logout();
      return;
    }

    if (error instanceof NotFoundException) {
      toast.error(error.message);
      return;
    }

    if (error instanceof NetworkException) {
      toast.error('Error de red: No fue posible conectar con el servidor.');
      return;
    }

    if (error instanceof InfrastructureException) {
      toast.error(error.message);
      return;
    }

    if (error instanceof Error) {
      toast.error(error.message || 'Ocurrió un error inesperado');
    } else {
      toast.error('Error desconocido');
    }
  }
}