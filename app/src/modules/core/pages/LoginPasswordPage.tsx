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
      tenant_id: tenants && tenants.length > 0 ? tenants[0].tenant_id : undefined,
    },
  });

  const onSubmit = async (values: LoginPasswordFormValues) => {
    if (!preAuthToken) return;

    setIsSubmitting(true);
    try {
      const response = await loginPasswordAction(values.password, values.tenant_id);

      setSessionData(
        response.data.access_token,
        response.data.user,
        values.tenant_id
      );

      navigate('/dashboard');
    } catch {
      // Manejado globalmente
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-slate-50 p-4 font-sans">
      <div className="w-full max-w-md rounded-xl bg-white p-8 shadow-lg border border-emerald-100">
        <div className="mb-6 text-center">
          <h2 className="text-2xl font-bold text-slate-800">Ingresar Contraseña</h2>
          <p className="mt-1 text-sm text-slate-500">
            Completa tus credenciales para ingresar a la plataforma
          </p>
        </div>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4" noValidate>
          {tenants && tenants.length > 1 && (
            <div>
              <label className="block text-sm font-medium text-slate-700">
                Seleccionar Empresa / Organización (Opcional)
              </label>
              <select
                {...register('tenant_id', {
                  setValueAs: (value) => (value ? Number(value) : undefined),
                })}
                className={cn(
                  'mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-slate-900 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500',
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
            <label className="block text-sm font-medium text-slate-700">
              Contraseña
            </label>
            <input
              type="password"
              {...register('password')}
              className={cn(
                'mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-slate-900 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500',
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
            className="w-full rounded-md bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 transition-colors"
          >
            {isSubmitting ? 'Iniciando sesión...' : 'Iniciar Sesión'}
          </button>
        </form>

        <div className="mt-6 text-center text-sm">
          <button
            type="button"
            onClick={() => navigate('/login')}
            className="font-medium text-emerald-600 hover:underline"
          >
            ← Volver a ingresar correo
          </button>
        </div>
      </div>
    </div>
  );
};