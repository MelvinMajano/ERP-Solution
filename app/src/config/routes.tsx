import { createBrowserRouter } from 'react-router-dom';
import { RegisterView } from '@modules/core/views/RegisterView';
import { CheckEmailView } from '@modules/core/views/CheckEmailView';
import { LoginPasswordView } from '@modules/core/views/LoginPasswordView';

export const router = createBrowserRouter([
  {
    path: '/register',
    element: <RegisterView />,
  },
  {
    path: '/login',
    element: <CheckEmailView />,
  },
  {
    path: '/login/password',
    element: <LoginPasswordView />,
  },
]);