import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import type { AuthState } from '@modules/core/types/auth.types';

export const useAuthStore = create<AuthState>()(
  persist(
    (set) => ({
      preAuthToken: null,
      sessionToken: null,
      tenantId: null,
      user: null,
      tenants: [],

      setPreAuthData: (token, tenants) =>
        set({
          preAuthToken: token,
          tenants: tenants,
        }),

      setSessionData: (token, user, tenantId) =>
        set((state) => ({
          sessionToken: token,
          user: user,
          tenantId: tenantId ?? user.tenant_id ?? state.tenantId ?? state.tenants[0]?.tenant_id ?? null,
          preAuthToken: null,
        })),

      setSelectedTenantId: (tenantId) => set({ tenantId }),

      logout: () =>
        set({
          preAuthToken: null,
          sessionToken: null,
          tenantId: null,
          user: null,
          tenants: [],
        }),
    }),
    {
      name: 'erp-auth-storage',
    }
  )
);