import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { Loader2, Mail, ArrowRight } from 'lucide-react';
import { checkEmailSchema, type CheckEmailFormValues} from '@domain/schemas/auth.schema';
import { checkEmailAction } from '../actions/checkEmailAction';
import { useAuthStore } from '../store/useAuthStore';
import { Button } from '@infrastructure/components/ui/button';
import { Input } from '@infrastructure/components/ui/input';
import { cn } from '@infrastructure/utils/cn';

interface FormStepEmailProps {
  onSuccess: () => void;
  onErrorToast?: (message: string) => void;
}

export const FormStepEmail: React.FC<FormStepEmailProps> = ({ onSuccess, onErrorToast }) => {
  const [isLoading, setIsLoading] = useState(false);
  const setPreAuthData = useAuthStore((state) => state.setPreAuthData);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<CheckEmailFormValues>({
    resolver: zodResolver(checkEmailSchema),
  });

  const onSubmit = async (data: CheckEmailFormValues) => {
    setIsLoading(true);
    try {
      const response = await checkEmailAction(data.email);
      if (response.status === 'success' && response.data) {
        setPreAuthData(response.data.pre_auth_token, response.data.tenants);
        onSuccess();
      } else {
        onErrorToast?.(response.message || 'El correo electrónico no existe en el sistema.');
      }
    } catch (error: any) {
      const msg = error?.response?.data?.message || 'El correo electrónico no existe o no está registrado.';
      onErrorToast?.(msg);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4" noValidate>
      <div className="space-y-1">
        <label className="text-sm font-medium text-foreground">
          Correo Electrónico
        </label>
        <div className="relative">
          <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            {...register('email')}
            type="email"
            placeholder="ejemplo@empresa.com"
            disabled={isLoading}
            className={cn('pl-9', errors.email && 'border-destructive focus-visible:ring-destructive')}
          />
        </div>
        {errors.email && (
          <p className="text-xs text-destructive font-medium">{errors.email.message}</p>
        )}
      </div>

      <Button type="submit" disabled={isLoading} className="w-full">
        {isLoading ? (
          <>
            <Loader2 className="mr-2 h-4 w-4 animate-spin" /> Validando...
          </>
        ) : (
          <>
            Siguiente <ArrowRight className="ml-2 h-4 w-4" />
          </>
        )}
      </Button>
    </form>
  );
};