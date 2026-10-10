import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { Loader2, Lock, ArrowLeft, Building2 } from 'lucide-react';
import { loginPasswordSchema, type LoginPasswordFormValues } from '@domain/schemas/auth.schema';
import { loginPasswordAction } from '../actions/loginPasswordAction';
import { useAuthStore } from '../store/useAuthStore';
import { Button } from '@infrastructure/components/ui/button';
import { Input } from '@infrastructure/components/ui/input';
import { cn } from '@infrastructure/utils/cn';

interface FormStepPasswordProps {
  onBack: () => void;
  onSuccess: () => void;
}

export const FormStepPassword: React.FC<FormStepPasswordProps> = ({ onBack, onSuccess }) => {
  const [isLoading, setIsLoading] = useState(false);
  const { tenants, setSessionData } = useAuthStore();

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<LoginPasswordFormValues>({
    resolver: zodResolver(loginPasswordSchema),
    defaultValues: {
      tenant_id: tenants.length > 0 ? tenants[0].tenant_id : undefined,
    },
  });

  const onSubmit = async (data: LoginPasswordFormValues) => {
    setIsLoading(true);
    try {
      const response = await loginPasswordAction(data.password, data.tenant_id);
      if (response.status === 'success' && response.data) {
        setSessionData(response.data.access_token, response.data.user, data.tenant_id);
        onSuccess();
      }
    } catch {
      // Manejado globalmente por el ErrorHandler de Axios
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4" noValidate>
      {tenants.length > 1 && (
        <div className="space-y-1">
          <label className="text-sm font-medium text-slate-700 flex items-center gap-1.5">
            <Building2 className="h-4 w-4 text-slate-400" /> Seleccione Organización
          </label>
          <select
            {...register('tenant_id', { valueAsNumber: true })}
            disabled={isLoading}
            className="w-full h-9 rounded-md border border-slate-300 bg-white px-3 py-1 text-sm text-slate-900 shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-emerald-500 disabled:opacity-50"
          >
            {tenants.map((t) => (
              <option key={t.tenant_id} value={t.tenant_id}>
                {t.company_name}
              </option>
            ))}
          </select>
        </div>
      )}

      <div className="space-y-1">
        <label className="text-sm font-medium text-slate-700">Contraseña</label>
        <div className="relative">
          <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
          <Input
            {...register('password')}
            type="password"
            placeholder="••••••••"
            disabled={isLoading}
            className={cn(
              'pl-9 border-slate-300 bg-white text-slate-900 focus-visible:ring-emerald-500 focus-visible:border-emerald-500',
              errors.password && 'border-red-500 focus-visible:ring-red-500'
            )}
          />
        </div>
        {errors.password && (
          <p className="text-xs text-red-600 font-medium">{errors.password.message}</p>
        )}
      </div>

      <div className="flex items-center gap-2 pt-2">
        <Button
          type="button"
          variant="outline"
          onClick={onBack}
          disabled={isLoading}
          className="w-1/3 border-slate-300 text-slate-700 hover:bg-slate-50"
        >
          <ArrowLeft className="mr-2 h-4 w-4" /> Volver
        </Button>
        <Button 
          type="submit" 
          disabled={isLoading} 
          className="w-2/3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors"
        >
          {isLoading ? (
            <>
              <Loader2 className="mr-2 h-4 w-4 animate-spin" /> Verificando...
            </>
          ) : (
            'Iniciar Sesión'
          )}
        </Button>
      </div>
    </form>
  );
};