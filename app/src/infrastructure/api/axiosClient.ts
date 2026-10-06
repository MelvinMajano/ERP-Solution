import axios, { AxiosError } from 'axios';
import { useAuthStore } from '@modules/core/store/useAuthStore';
import { ErrorHandler } from '@infrastructure/exceptions/ErrorHandler';

export const axiosClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL || import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

axiosClient.interceptors.request.use((config) => {
  const { sessionToken, preAuthToken, tenantId } = useAuthStore.getState();
  const token = sessionToken || preAuthToken;

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  if (tenantId) {
    config.headers['X-Tenant-ID'] = tenantId;
  }

  return config;
});

axiosClient.interceptors.response.use(
  (response) => response,
  (error: AxiosError<any>) => {
    const exception = ErrorHandler.fromAxios(error);
    ErrorHandler.handle(exception);
    return Promise.reject(exception);
  }
);