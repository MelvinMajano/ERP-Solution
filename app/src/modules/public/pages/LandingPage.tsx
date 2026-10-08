import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Layers, CheckCircle2, ShieldCheck, Zap, AlertCircle } from 'lucide-react';

// Primitivos de Infraestructura (Shadcn UI)
import { Button } from '@infrastructure/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@infrastructure/components/ui/card';

// Componentes del Módulo Core (Autenticación)
import { FormStepEmail } from '@modules/core/components/FormStepEmail';
import { FormStepPassword } from '@modules/core/components/FormStepPassword';
import { FormRegisterTenant } from '@modules/core/components/FormRegisterTenant';

export const LandingPage: React.FC = () => {
  const navigate = useNavigate();

  // Control de Modales
  const [isLoginOpen, setIsLoginOpen] = useState(false);
  const [isRegisterOpen, setIsRegisterOpen] = useState(false);
  const [loginStep, setLoginStep] = useState<'email' | 'password'>('email');

  // Estado Toast
  const [toastMessage, setToastMessage] = useState<string | null>(null);

  const showToast = (message: string) => {
    setToastMessage(message);
    setTimeout(() => setToastMessage(null), 4000);
  };

  const handleOpenLogin = () => {
    setLoginStep('email');
    setIsRegisterOpen(false);
    setIsLoginOpen(true);
  };

  const handleOpenRegister = () => {
    setIsLoginOpen(false);
    setIsRegisterOpen(true);
  };

  const handleCloseModals = () => {
    setIsLoginOpen(false);
    setIsRegisterOpen(false);
    setLoginStep('email');
  };

  return (
    <div className="min-h-screen w-full bg-background text-foreground flex flex-col justify-between overflow-hidden relative font-sans">
      {/* Toast Notificación */}
      {toastMessage && (
        <div className="fixed top-5 right-5 z-50 flex items-center gap-2 bg-destructive text-destructive-foreground px-4 py-3 rounded-lg shadow-lg border border-destructive/20 animate-in fade-in duration-300">
          <AlertCircle className="h-5 w-5 shrink-0" />
          <span className="text-sm font-medium">{toastMessage}</span>
        </div>
      )}

      {/* Public Navbar */}
      <header className="w-full border-b border-border/40 bg-background/95 backdrop-blur px-6 py-4 flex items-center justify-between">
        <div className="flex items-center gap-2">
          <div className="p-2 rounded-xl bg-primary text-primary-foreground">
            <Layers className="h-6 w-6" />
          </div>
          <span className="text-xl font-bold tracking-tight">ERP-Solution</span>
        </div>

        <div className="flex items-center gap-3">
          <Button variant="ghost" onClick={handleOpenLogin}>
            Iniciar Sesión
          </Button>
          <Button onClick={handleOpenRegister}>
            Registrarse
          </Button>
        </div>
      </header>

      {/* Main Banner Card (Sin Scroll) */}
      <main className="flex-1 flex items-center justify-center p-6">
        <Card className="w-full max-w-5xl shadow-2xl border-border/60 bg-card">
          <CardContent className="p-8 md:p-12 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div className="space-y-6 text-left">
              <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold">
                <Zap className="h-3.5 w-3.5" /> Arquitectura Multi-Tenant de Élite
              </div>
              <h1 className="text-4xl md:text-5xl font-extrabold tracking-tight leading-tight">
                Gestión Empresarial Inteligente y Escalable
              </h1>
              <p className="text-muted-foreground text-base leading-relaxed">
                Centraliza la operatividad de tu empresa con control total de inventarios, ventas, compras y usuarios con aislamiento garantizado.
              </p>
              <div className="space-y-2 pt-2">
                <div className="flex items-center gap-2 text-sm text-foreground/80">
                  <CheckCircle2 className="h-4 w-4 text-primary" /> Módulos desacoplados y personalizables
                </div>
                <div className="flex items-center gap-2 text-sm text-foreground/80">
                  <ShieldCheck className="h-4 w-4 text-primary" /> Autenticación segura de dos pasos
                </div>
              </div>
            </div>

            <div className="bg-muted/40 p-6 rounded-2xl border border-border/50 flex flex-col justify-between h-full space-y-6">
              <div className="space-y-2 text-left">
                <h2 className="text-xl font-bold">Comienza en segundos</h2>
                <p className="text-sm text-muted-foreground">
                  Elige una opción para acceder a tu entorno corporativo o dar de alta tu organización.
                </p>
              </div>

              <div className="space-y-3">
                <Button onClick={handleOpenLogin} className="w-full h-11 text-base">
                  Acceder a mi Cuenta
                </Button>
                <Button onClick={handleOpenRegister} variant="outline" className="w-full h-11 text-base">
                  Registrar mi Empresa
                </Button>
              </div>

              <div className="text-xs text-muted-foreground text-center pt-2">
                Plataforma segura e insulada por subdominio e identificador de tenant.
              </div>
            </div>
          </CardContent>
        </Card>
      </main>

      <footer className="py-4 text-center text-xs text-muted-foreground border-t border-border/30">
        © {new Date().getFullYear()} ERP-Solution. Todos los derechos reservados.
      </footer>

      {/* Modal Iniciar Sesión (Módulo Core) */}
      {isLoginOpen && (
        <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 animate-in fade-in duration-200">
          <Card className="w-full max-w-md relative shadow-2xl">
            <button
              onClick={handleCloseModals}
              className="absolute right-4 top-4 text-muted-foreground hover:text-foreground"
            >
              ✕
            </button>
            <CardHeader className="text-left">
              <CardTitle className="text-2xl font-bold">
                {loginStep === 'email' ? 'Iniciar Sesión' : 'Ingrese su Contraseña'}
              </CardTitle>
              <CardDescription>
                {loginStep === 'email'
                  ? 'Ingrese su correo para validar su cuenta corporativa'
                  : 'Paso 2 de autenticación'}
              </CardDescription>
            </CardHeader>
            <CardContent>
              {loginStep === 'email' ? (
                <FormStepEmail
                  onSuccess={() => setLoginStep('password')}
                  onErrorToast={(msg) => showToast(msg)}
                />
              ) : (
                <FormStepPassword
                  onBack={() => setLoginStep('email')}
                  onSuccess={() => {
                    handleCloseModals();
                    navigate('/dashboard');
                  }}
                />
              )}
            </CardContent>
          </Card>
        </div>
      )}

      {/* Modal Registrar Empresa (Módulo Core) */}
      {isRegisterOpen && (
        <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 animate-in fade-in duration-200">
          <Card className="w-full max-w-lg relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button
              onClick={handleCloseModals}
              className="absolute right-4 top-4 text-muted-foreground hover:text-foreground z-10"
            >
              ✕
            </button>
            <CardHeader className="text-left">
              <CardTitle className="text-2xl font-bold">Registrar Nueva Empresa</CardTitle>
              <CardDescription>
                Crea el espacio de trabajo para tu organización y su cuenta administradora
              </CardDescription>
            </CardHeader>
            <CardContent>
              <FormRegisterTenant
                onSuccess={() => {
                  handleCloseModals();
                  showToast('¡Empresa registrada con éxito! Ahora puedes iniciar sesión.');
                  handleOpenLogin();
                }}
              />
            </CardContent>
          </Card>
        </div>
      )}
    </div>
  );
};