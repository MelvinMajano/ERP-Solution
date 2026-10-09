import { createBrowserRouter } from 'react-router-dom';
<<<<<<< HEAD
import { LandingPage } from '@modules/public/pages/LandingPage';
import { CheckEmailPage } from '@modules/core/pages/CheckEmailPage';
import { LoginPasswordPage } from '@modules/core/pages/LoginPasswordPage';
import { RegisterPage } from '@modules/core/pages/RegisterPage';
=======
import { RegisterView } from '@modules/core/views/RegisterView';
import { CheckEmailView } from '@modules/core/views/CheckEmailView';
import { LoginPasswordView } from '@modules/core/views/LoginPasswordView';
import { LandingPage } from '@modules/public/pages/LandingPage';
>>>>>>> 8382339 (feat: se añadio las configuraciones basicas del front)

export const router = createBrowserRouter([
  {
    path: '/',
<<<<<<< HEAD
    element: <LandingPage />,
=======
    element: <LandingPage/>,
  },
  {
    path: '/register',
    element: <RegisterView />,
>>>>>>> 8382339 (feat: se añadio las configuraciones basicas del front)
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