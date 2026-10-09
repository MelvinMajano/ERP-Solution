import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { loginPasswordSchema, type LoginPasswordFormValues } from '@domain/schemas/auth.schema';
import { loginPasswordAction } from '@modules/core/actions/loginPasswordAction';
import { useAuthStore } from '@modules/core/store/useAuthStore';
import { cn } from '@infrastructure/utils/cn';

export const LoginPasswordPage: React.FC = () => {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const navigate = useNavigate();

  const preAuthToken = useAuthStore((state) => state.preAuthToken);
  const tenants = useAuthStore((state) => state.tenants);
  const setSessionData = useAuthStore((state) => state.setSessionData);

  // Si no hay un token temporal de verificación de correo, redirige al paso 1
  useEffect(() => {
    if (!preAuthToken) {
      navigate('/login');
    }
  }, [preAuthToken, navigate]);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<LoginPasswordFormValues>({
    resolver: zodResolver(loginPasswordSchema),
    defaultValues: {
      password: '',
      // Usamos tenant_id en lugar de id
      tenant_id: tenants && tenants.length > 0 ? tenants[0].tenant_id : undefined,
    },
  });

  const onSubmit = async (values: LoginPasswordFormValues) => {
    if (!preAuthToken) return;

    setIsSubmitting(true);
    try {
      const response = await loginPasswordAction(values.password, values.tenant_id);

      // Guardamos la sesión en el store global Zustand
      setSessionData(
        response.data.access_token,
        response.data.user,
        values.tenant_id
      );

      navigate('/dashboard');
    } catch {
      // Los errores son procesados globalmente por el ErrorHandler/Axios
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-100 p-4 dark:bg-gray-900">
      <div className="w-full max-w-md rounded-xl bg-white p-6 shadow-md border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
        <div className="mb-6 text-center">
          <h2 className="text-2xl font-bold text-gray-800 dark:text-white">Ingresar Contraseña</h2>
          <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Completa tus credenciales para ingresar a la plataforma
          </p>
        </div>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4" noValidate>
          {/* Si el usuario tiene más de 1 tenant asociado, mostramos el selector opcional */}
          {tenants && tenants.length > 1 && (
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Seleccionar Empresa / Organización (Opcional)
              </label>
              <select
                {...register('tenant_id', {
                  setValueAs: (value) => (value ? Number(value) : undefined),
                })}
                className={cn(
                  'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white',
                  errors.tenant_id && 'border-red-500 focus:border-red-500'
                )}
              >
                {tenants.map((tenant) => (
                  <option key={tenant.tenant_id} value={tenant.tenant_id}>
                    {tenant.company_name} ({tenant.subdomain})
                  </option>
                ))}
              </select>
              {errors.tenant_id && (
                <p className="mt-1 text-xs text-red-600">{errors.tenant_id.message}</p>
              )}
            </div>
          )}

          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Contraseña
            </label>
            <input
              type="password"
              {...register('password')}
              className={cn(
                'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white',
                errors.password && 'border-red-500 focus:border-red-500 focus:ring-red-500'
              )}
              placeholder="••••••••"
            />
            {errors.password && (
              <p className="mt-1 text-xs text-red-600">{errors.password.message}</p>
            )}
          </div>

          <button
            type="submit"
            disabled={isSubmitting}
            className="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
          >
            {isSubmitting ? 'Iniciando sesión...' : 'Iniciar Sesión'}
          </button>
        </form>

        <div className="mt-6 text-center text-sm">
          <button
            type="button"
            onClick={() => navigate('/login')}
            className="font-medium text-blue-600 hover:underline dark:text-blue-400"
          >
            ← Volver a ingresar correo
          </button>
        </div>
      </div>
    </div>
  );
};