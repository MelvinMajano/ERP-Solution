import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { registerTenantSchema, type RegisterTenantFormValues } from '@domain/schemas/auth.schema';
import { registerTenantAction } from '@modules/core/actions/registerTenantAction';
import { cn } from '@infrastructure/utils/cn';

interface FormRegisterTenantProps {
  onSuccess?: () => void;
  className?: string;
}

export const FormRegisterTenant: React.FC<FormRegisterTenantProps> = ({ onSuccess, className }) => {
  const [isSubmitting, setIsSubmitting] = useState(false);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<RegisterTenantFormValues>({
    resolver: zodResolver(registerTenantSchema),
    defaultValues: {
      company_name: '',
      subdomain: '',
      first_names: '',
      last_names: '',
      username: '',
      email: '',
      password: '',
    },
  });

  const onSubmit = async (values: RegisterTenantFormValues) => {
    setIsSubmitting(true);
    try {
      await registerTenantAction(values);
      if (onSuccess) {
        onSuccess();
      }
    } catch {
      // Los errores HTTP son manejados globalmente por el ErrorHandler/Axios interceptor
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className={cn('space-y-4', className)} noValidate>
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label className="block text-sm font-medium text-gray-700">Nombre de la Empresa</label>
          <input
            type="text"
            {...register('company_name')}
            className={cn(
              'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm',
              errors.company_name && 'border-red-500 focus:border-red-500 focus:ring-red-500'
            )}
            placeholder="Mi Empresa S.A."
          />
          {errors.company_name && (
            <p className="mt-1 text-xs text-red-600">{errors.company_name.message}</p>
          )}
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">Subdominio</label>
          <input
            type="text"
            {...register('subdomain')}
            className={cn(
              'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm',
              errors.subdomain && 'border-red-500 focus:border-red-500 focus:ring-red-500'
            )}
            placeholder="mi-empresa"
          />
          {errors.subdomain && (
            <p className="mt-1 text-xs text-red-600">{errors.subdomain.message}</p>
          )}
        </div>
      </div>

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label className="block text-sm font-medium text-gray-700">Nombres</label>
          <input
            type="text"
            {...register('first_names')}
            className={cn(
              'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm',
              errors.first_names && 'border-red-500 focus:border-red-500 focus:ring-red-500'
            )}
            placeholder="Juan"
          />
          {errors.first_names && (
            <p className="mt-1 text-xs text-red-600">{errors.first_names.message}</p>
          )}
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">Apellidos</label>
          <input
            type="text"
            {...register('last_names')}
            className={cn(
              'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm',
              errors.last_names && 'border-red-500 focus:border-red-500 focus:ring-red-500'
            )}
            placeholder="Pérez"
          />
          {errors.last_names && (
            <p className="mt-1 text-xs text-red-600">{errors.last_names.message}</p>
          )}
        </div>
      </div>

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label className="block text-sm font-medium text-gray-700">Nombre de Usuario</label>
          <input
            type="text"
            {...register('username')}
            className={cn(
              'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm',
              errors.username && 'border-red-500 focus:border-red-500 focus:ring-red-500'
            )}
            placeholder="jperez"
          />
          {errors.username && (
            <p className="mt-1 text-xs text-red-600">{errors.username.message}</p>
          )}
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">Correo Electrónico</label>
          <input
            type="email"
            {...register('email')}
            className={cn(
              'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm',
              errors.email && 'border-red-500 focus:border-red-500 focus:ring-red-500'
            )}
            placeholder="admin@empresa.com"
          />
          {errors.email && (
            <p className="mt-1 text-xs text-red-600">{errors.email.message}</p>
          )}
        </div>
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700">Contraseña</label>
        <input
          type="password"
          {...register('password')}
          className={cn(
            'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm',
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
        {isSubmitting ? 'Registrando...' : 'Registrar Empresa'}
      </button>
    </form>
  );
};