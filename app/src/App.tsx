import './App.css'
import { RouterProvider } from 'react-router-dom'
import { router } from '@config/routes'

const App =() =>{
  return <RouterProvider router={router} />;
}

export default App
