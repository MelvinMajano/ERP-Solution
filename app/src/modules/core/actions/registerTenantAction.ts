import { axiosClient } from '@infrastructure/api/axiosClient';
import type { ApiResponse } from '@domain/types/api.types';
import type { RegisterTenantPayload } from '@modules/core/types/auth.types';

export const registerTenantAction = async (
  payload: RegisterTenantPayload
): Promise<ApiResponse<any>> => {
  const { data } = await axiosClient.post<ApiResponse<any>>('/auth/register-tenant', payload);
  return data;
};