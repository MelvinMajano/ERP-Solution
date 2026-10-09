import React from 'react';
import { useNavigate } from 'react-router-dom';
import { 
  Layers, 
  CheckCircle2, 
  ShieldCheck, 
  Zap 
} from 'lucide-react';

import { Button } from '@infrastructure/components/ui/button';
import { Card, CardContent } from '@infrastructure/components/ui/card';
import { ProcessGearsIllustration } from '@modules/public/components/ProcessGearsIllustration';

export const LandingPage: React.FC = () => {
  const navigate = useNavigate();

  return (
    <div className="h-screen w-screen bg-slate-950 text-slate-100 flex flex-col justify-between overflow-hidden relative font-sans selection:bg-emerald-500 selection:text-slate-950">
      {/* Public Navbar */}
      <header className="w-full h-16 border-b border-emerald-900/40 bg-slate-950/80 backdrop-blur px-8 flex items-center justify-between shrink-0 z-20">
        <div className="flex items-center gap-3 cursor-pointer" onClick={() => navigate('/')}>
          <div className="p-2 rounded-xl bg-gradient-to-tr from-emerald-600 to-emerald-400 text-slate-950 shadow-lg shadow-emerald-500/20">
            <Layers className="h-5 w-5 stroke-[2.5]" />
          </div>
          <span className="text-xl font-bold tracking-tight text-white">ERP-Solution</span>
        </div>

        <div className="flex items-center gap-3">
          <Button 
            variant="ghost" 
            onClick={() => navigate('/login')} 
            className="text-slate-300 hover:text-emerald-400 hover:bg-emerald-950/40 font-medium"
          >
            Iniciar Sesión
          </Button>
          <Button 
            onClick={() => navigate('/register')} 
            className="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold shadow-md shadow-emerald-500/20 px-5"
          >
            Registrarse
          </Button>
        </div>
      </header>

      {/* Main Container a Full Viewport */}
      <main className="flex-1 w-full h-[calc(100vh-4rem)] p-4 md:p-6 flex items-center justify-center overflow-hidden">
        <Card className="w-full h-full shadow-2xl border-emerald-800/30 bg-gradient-to-br from-emerald-950/60 via-slate-900 to-slate-950 relative overflow-hidden flex flex-col justify-center rounded-3xl">
          <div className="absolute -top-40 -left-40 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl pointer-events-none" />
          <div className="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-emerald-600/10 rounded-full blur-3xl pointer-events-none" />

          <CardContent className="p-8 md:p-14 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center h-full z-10">
            <div className="lg:col-span-5 space-y-8 text-left my-auto">
              <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-900/50 border border-emerald-500/30 text-emerald-300 text-xs font-semibold tracking-wide uppercase">
                <Zap className="h-3.5 w-3.5 text-emerald-400" /> Arquitectura Multi-Tenant de Élite
              </div>
              
              <h1 className="text-4xl sm:text-5xl xl:text-6xl font-extrabold tracking-tight leading-[1.1] text-white">
                Gestión Empresarial <br />
                <span className="bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-200 bg-clip-text text-transparent">
                  Inteligente y Escalable
                </span>
              </h1>

              <p className="text-slate-300 text-base xl:text-lg leading-relaxed">
                Centraliza la operatividad de tu empresa con control total de inventarios, ventas, compras y usuarios con aislamiento de datos garantizado.
              </p>

              <div className="space-y-4 pt-2">
                <div className="flex items-center gap-3 text-slate-200 text-sm xl:text-base">
                  <div className="h-6 w-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                    <CheckCircle2 className="h-4 w-4" />
                  </div>
                  <span>Módulos desacoplados y personalizables</span>
                </div>
                <div className="flex items-center gap-3 text-slate-200 text-sm xl:text-base">
                  <div className="h-6 w-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                    <ShieldCheck className="h-4 w-4" />
                  </div>
                  <span>Autenticación segura de dos pasos</span>
                </div>
              </div>
            </div>

            <div className="lg:col-span-7 w-full h-full flex items-center justify-center my-auto relative">
              <ProcessGearsIllustration />
            </div>
          </CardContent>
        </Card>
      </main>

      {/* Footer Minimalista */}
      <footer className="h-8 py-1 text-center text-xs text-slate-500 border-t border-emerald-900/20 shrink-0 z-20 flex items-center justify-center">
        &copy; {new Date().getFullYear()} ERP-Solution. Todos los derechos reservados. Entorno multi-tenant insulado.
      </footer>
    </div>
  );
};