import { axiosClient } from '@infrastructure/api/axiosClient';
import type { ApiResponse } from '@domain/types/api.types';
import type { CheckEmailData } from '@modules/core/types/auth.types';

export const checkEmailAction = async (email: string): Promise<ApiResponse<CheckEmailData>> => {
  const { data } = await axiosClient.post<ApiResponse<CheckEmailData>>('/auth/check-email', { email });
  return data;
};