import { axiosClient } from '@infrastructure/api/axiosClient';
import type { ApiResponse } from '@domain/types/api.types';
import type { LoginPasswordData } from '@modules/core/types/auth.types';

export const loginPasswordAction = async (
  password: string,
  tenantId?: number
): Promise<ApiResponse<LoginPasswordData>> => {
  const { data } = await axiosClient.post<ApiResponse<LoginPasswordData>>('/auth/login-password', {
    password,
    tenant_id: tenantId,
  });
  return data;
};