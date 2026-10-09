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
      // Guarda token temporal y tenants en Zustand
      setPreAuthData(response.data.pre_auth_token, response.data.tenants);
      // Redirige al paso 2 de ingreso de contraseña
      navigate('/login/password');
    } catch {
      // Manejado globalmente por el ErrorHandler
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-100 p-4 dark:bg-gray-900">
      <div className="w-full max-w-md rounded-xl bg-white p-6 shadow-md border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
        <div className="mb-6 text-center">
          <h2 className="text-2xl font-bold text-gray-800 dark:text-white">Iniciar Sesión</h2>
          <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Ingresa tu correo electrónico para verificar tu cuenta
          </p>
        </div>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4" noValidate>
          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Correo Electrónico
            </label>
            <input
              type="email"
              {...register('email')}
              className={cn(
                'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white',
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
            className="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
          >
            {isSubmitting ? 'Verificando...' : 'Continuar'}
          </button>
        </form>

        <div className="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
          ¿No tienes una empresa registrada?{' '}
          <Link
            to="/register"
            className="font-medium text-blue-600 hover:underline dark:text-blue-400"
          >
            Regístrate aquí
          </Link>
        </div>
      </div>
    </div>
  );
};