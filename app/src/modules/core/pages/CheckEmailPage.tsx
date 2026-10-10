import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { checkEmailSchema, type CheckEmailFormValues } from '@domain/schemas/auth.schema';
import { checkEmailAction } from '@modules/core/actions/checkEmailAction';
import { useAuthStore } from '@modules/core/store/useAuthStore';
import { cn } from '@infrastructure/utils/cn';

export const CheckEmailPage: React.FC = () => {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const setPreAuthData = useAuthStore((state) => state.setPreAuthData);
  const navigate = useNavigate();

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<CheckEmailFormValues>({
    resolver: zodResolver(checkEmailSchema),
    defaultValues: {
      email: '',
    },
  });

  const onSubmit = async (values: CheckEmailFormValues) => {
    setIsSubmitting(true);
    try {
      const response = await checkEmailAction(values.email);
      setPreAuthData(response.data.pre_auth_token, response.data.tenants);
      navigate('/login/password');
    } catch {
      // Manejado globalmente por el Interceptor
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-slate-50 p-4 font-sans">
      <div className="w-full max-w-md rounded-xl bg-white p-8 shadow-lg border border-emerald-100">
        <div className="mb-6 text-center">
          <h2 className="text-2xl font-bold text-slate-800">Iniciar Sesión</h2>
          <p className="mt-1 text-sm text-slate-500">
            Ingresa tu correo electrónico para verificar tu cuenta
          </p>
        </div>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4" noValidate>
          <div>
            <label className="block text-sm font-medium text-slate-700">
              Correo Electrónico
            </label>
            <input
              type="email"
              {...register('email')}
              className={cn(
                'mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-slate-900 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500',
                errors.email && 'border-red-500 focus:border-red-500 focus:ring-red-500'
              )}
              placeholder="ejemplo@empresa.com"
            />
            {errors.email && (
              <p className="mt-1 text-xs text-red-600">{errors.email.message}</p>
            )}
          </div>

          <button
            type="submit"
            disabled={isSubmitting}
            className="w-full rounded-md bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 transition-colors"
          >
            {isSubmitting ? 'Verificando...' : 'Continuar'}
          </button>
        </form>

        <div className="mt-6 text-center text-sm text-slate-500">
          ¿No tienes una empresa registrada?{' '}
          <Link
            to="/register"
            className="font-medium text-emerald-600 hover:underline"
          >
            Regístrate aquí
          </Link>
        </div>
      </div>
    </div>
  );
};