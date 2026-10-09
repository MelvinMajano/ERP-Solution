import React from 'react';
import { 
  Lightbulb, 
  Target, 
  BarChart2, 
  Briefcase, 
  Users, 
  Handshake, 
  DollarSign 
} from 'lucide-react';

// Helper para dibujar engranajes vectoriales precisos con rotación
const GearShape: React.FC<{
  size: number;
  teeth?: number;
  rotate?: number;
  colorClass?: string;
  children?: React.ReactNode;
}> = ({ size, teeth = 10, rotate = 0, colorClass = "fill-slate-950 stroke-emerald-400", children }) => {
  const radius = size / 2;
  const outerRadius = radius * 0.96;
  const innerRadius = radius * 0.74;
  const holeRadius = radius * 0.42;
  const center = radius;

  const points: string[] = [];
  for (let i = 0; i < teeth; i++) {
    const angle1 = (i * 2 * Math.PI) / teeth;
    const angle2 = ((i + 0.28) * 2 * Math.PI) / teeth;
    const angle3 = ((i + 0.5) * 2 * Math.PI) / teeth;
    const angle4 = ((i + 0.78) * 2 * Math.PI) / teeth;

    points.push(`${center + outerRadius * Math.cos(angle1)},${center + outerRadius * Math.sin(angle1)}`);
    points.push(`${center + outerRadius * Math.cos(angle2)},${center + outerRadius * Math.sin(angle2)}`);
    points.push(`${center + innerRadius * Math.cos(angle3)},${center + innerRadius * Math.sin(angle3)}`);
    points.push(`${center + innerRadius * Math.cos(angle4)},${center + innerRadius * Math.sin(angle4)}`);
  }

  const gearPath = `M ${points.join(' L ')} Z`;

  return (
    <div 
      className="relative flex items-center justify-center shrink-0" 
      style={{ width: size, height: size, transform: `rotate(${rotate}deg)` }}
    >
      <svg width={size} height={size} className="absolute inset-0">
        <path d={gearPath} className={`${colorClass} stroke-[2]`} />
        <circle cx={center} cy={center} r={holeRadius} className="fill-slate-950 stroke-emerald-500/60 stroke-[1.5]" />
      </svg>
      <div 
        className="relative z-10 flex items-center justify-center text-emerald-300"
        style={{ transform: `rotate(${-rotate}deg)` }}
      >
        {children}
      </div>
    </div>
  );
};

export const ProcessGearsIllustration: React.FC = () => {
  return (
    <div className="w-full max-w-2xl bg-emerald-950/20 border border-emerald-500/20 rounded-3xl p-8 backdrop-blur-md shadow-2xl relative overflow-hidden flex items-center justify-center min-h-[380px]">
      {/* Canvas de Posicionamiento para Encaje de Engranajes */}
      <div className="relative w-[520px] h-[320px]">
        {/* 1. Engranaje Central (Objetivos/Core) - Grande */}
        <div className="absolute top-[80px] left-[180px]">
          <GearShape size={160} teeth={12} rotate={0} colorClass="fill-slate-950 stroke-emerald-400">
            <Target className="w-9 h-9 text-emerald-400" />
          </GearShape>
        </div>

        {/* 2. Engranaje Superior Izquierdo (Idea) */}
        <div className="absolute top-[10px] left-[90px]">
          <GearShape size={110} teeth={10} rotate={18} colorClass="fill-slate-950 stroke-emerald-400">
            <Lightbulb className="w-6 h-6 text-emerald-300" />
          </GearShape>
        </div>

        {/* 3. Engranaje Extremo Izquierdo (Módulos/Maletín) */}
        <div className="absolute top-[110px] left-[15px]">
          <GearShape size={80} teeth={8} rotate={22.5} colorClass="fill-slate-950 stroke-emerald-400">
            <Briefcase className="w-5 h-5 text-emerald-400" />
          </GearShape>
        </div>

        {/* 4. Engranaje Inferior Izquierdo (Usuarios) */}
        <div className="absolute top-[185px] left-[95px]">
          <GearShape size={115} teeth={10} rotate={18} colorClass="fill-slate-950 stroke-emerald-400">
            <Users className="w-6 h-6 text-emerald-300" />
          </GearShape>
        </div>

        {/* 5. Engranaje Superior Derecho (Finanzas) */}
        <div className="absolute top-[15px] left-[320px]">
          <GearShape size={115} teeth={10} rotate={18} colorClass="fill-slate-950 stroke-emerald-400">
            <DollarSign className="w-7 h-7 text-emerald-400" />
          </GearShape>
        </div>

        {/* 6. Engranaje Extremo Derecho (Métricas) */}
        <div className="absolute top-[120px] left-[425px]">
          <GearShape size={80} teeth={8} rotate={22.5} colorClass="fill-slate-950 stroke-emerald-400">
            <BarChart2 className="w-5 h-5 text-emerald-400" />
          </GearShape>
        </div>

        {/* 7. Engranaje Inferior Derecho (Ventas) */}
        <div className="absolute top-[175px] left-[315px]">
          <GearShape size={125} teeth={10} rotate={18} colorClass="fill-slate-950 stroke-emerald-400">
            <Handshake className="w-7 h-7 text-emerald-300" />
          </GearShape>
        </div>
      </div>
    </div>
  );
};