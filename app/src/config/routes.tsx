import { createBrowserRouter } from 'react-router-dom';
import { LandingPage } from '@modules/public/pages/LandingPage';
import { CheckEmailPage } from '@modules/core/pages/CheckEmailPage';
import { LoginPasswordPage } from '@modules/core/pages/LoginPasswordPage';
import { RegisterPage } from '@modules/core/pages/RegisterPage';

export const router = createBrowserRouter([
  {
    path: '/',
    element: <LandingPage />,
  },
  {
    path: '/login',
    element: <CheckEmailPage />,
  },
  {
    path: '/login/password',
    element: <LoginPasswordPage />,
  },
  {
    path: '/register',
    element: <RegisterPage />,
  },
]);