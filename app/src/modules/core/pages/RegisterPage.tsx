import React from 'react';
import { useNavigate } from 'react-router-dom';
import { FormRegisterTenant } from '@modules/core/components/FormRegisterTenant';

export const RegisterPage: React.FC = () => {
  const navigate = useNavigate();

  const handleSuccess = () => {
    navigate('/login');
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-slate-50 p-4 font-sans">
      <div className="w-full max-w-lg rounded-xl bg-white p-8 shadow-lg border border-emerald-100">
        <div className="mb-6 text-center">
          <h1 className="text-2xl font-bold text-slate-900">Registrar Nueva Empresa</h1>
          <p className="mt-1 text-sm text-slate-500">
            Crea una cuenta para tu organización y comienza a usar el ERP
          </p>
        </div>

        <FormRegisterTenant onSuccess={handleSuccess} />

        <div className="mt-6 text-center text-sm text-slate-500">
          ¿Ya tienes una cuenta?{' '}
          <button
            type="button"
            onClick={() => navigate('/login')}
            className="font-medium text-emerald-600 hover:underline"
          >
            Iniciar sesión
          </button>
        </div>
      </div>
    </div>
  );
};