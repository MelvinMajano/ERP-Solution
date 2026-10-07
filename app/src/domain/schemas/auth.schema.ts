import { z } from 'zod';

export const checkEmailSchema = z.object({
  email: z
    .string()
    .min(1, 'El correo electrónico es requerido')
    .email('Ingrese un correo electrónico válido'),
});

export const loginPasswordSchema = z.object({
  password: z.string().min(1, 'La contraseña es requerida'),
  tenant_id: z.number().optional(),
});

export const registerTenantSchema = z.object({
  company_name: z.string().min(2, 'El nombre comercial debe tener al menos 2 caracteres'),
  subdomain: z
    .string()
    .min(2, 'El subdominio debe tener al menos 2 caracteres')
    .regex(/^[a-z0-9-]+$/, 'Solo se permiten letras minúsculas, números y guiones'),
  first_names: z.string().min(2, 'El nombre es requerido'),
  last_names: z.string().min(2, 'El apellido es requerido'),
  username: z.string().min(3, 'El usuario debe tener al menos 3 caracteres'),
  email: z.string().email('Ingrese un correo electrónico válido'),
  password: z.string().min(6, 'La contraseña debe tener al menos 6 caracteres'),
});

export type CheckEmailFormValues = z.infer<typeof checkEmailSchema>;
export type LoginPasswordFormValues = z.infer<typeof loginPasswordSchema>;
export type RegisterTenantFormValues = z.infer<typeof registerTenantSchema>;