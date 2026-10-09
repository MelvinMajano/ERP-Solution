import React from 'react';
import { useNavigate } from 'react-router-dom';
import { FormRegisterTenant } from '@modules/core/components/FormRegisterTenant';

export const RegisterPage: React.FC = () => {
  const navigate = useNavigate();

  const handleSuccess = () => {
    // Tras un registro exitoso, redirigimos al primer paso del Login (CheckEmail)
    navigate('/login');
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-50 p-4 dark:bg-gray-900">
      <div className="w-full max-w-lg rounded-xl bg-white p-6 shadow-lg border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
        <div className="mb-6 text-center">
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Registrar Nueva Empresa</h1>
          <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Crea una cuenta para tu organización y comienza a usar el ERP
          </p>
        </div>

        {/* Formulario modularizado */}
        <FormRegisterTenant onSuccess={handleSuccess} />

        <div className="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
          ¿Ya tienes una cuenta?{' '}
          <button
            type="button"
            onClick={() => navigate('/login')}
            className="font-medium text-blue-600 hover:underline dark:text-blue-400"
          >
            Iniciar sesión
          </button>
        </div>
      </div>
    </div>
  );
};