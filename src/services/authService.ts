/**
 * Servicio de Autenticación
 * Conectado a la API PHP Backend
 */

import { LoginCredentials, RegisterData, LoginResult, User } from '../types';
import axiosClient, { AUTH_TOKEN_KEY } from '../app/axiosClient';

/**
 * Login de usuario
 */
export const login = async (credentials: LoginCredentials): Promise<LoginResult> => {
  try {
    const response = await axiosClient.post('/auth/login', credentials);
    
    if (response.data.success && response.data.token) {
      // Guardar token en sessionStorage
      sessionStorage.setItem(AUTH_TOKEN_KEY, response.data.token);
    }
    
    return response.data;
  } catch (error: any) {
    return {
      success: false,
      message: error.response?.data?.message || 'Error al iniciar sesión',
    };
  }
};

/**
 * Registro de nuevo usuario (solo pacientes)
 */
export const register = async (data: RegisterData): Promise<LoginResult> => {
  try {
    const response = await axiosClient.post('/auth/register', data);
    
    if (response.data.success && response.data.token) {
      // Guardar token en sessionStorage
      sessionStorage.setItem(AUTH_TOKEN_KEY, response.data.token);
    }
    
    return response.data;
  } catch (error: any) {
    return {
      success: false,
      message: error.response?.data?.message || 'Error al registrar usuario',
    };
  }
};

/**
 * Obtener información del usuario actual
 */
export const getCurrentUser = async (): Promise<User | null> => {
  try {
    const token = sessionStorage.getItem(AUTH_TOKEN_KEY);
    if (!token) {
      return null;
    }
    
    const response = await axiosClient.get('/auth/me');
    
    if (response.data.success) {
      return response.data.user;
    }
    
    return null;
  } catch (error) {
    return null;
  }
};

/**
 * Cerrar sesión
 */
export const logout = (): void => {
  sessionStorage.removeItem(AUTH_TOKEN_KEY);
};

/**
 * Actualizar perfil de usuario
 */
export const updateProfile = async (userId: number | string, updates: Partial<User>): Promise<User | null> => {
  try {
    const response = await axiosClient.put('/auth/profile', updates);
    
    if (response.data.success) {
      return response.data.user;
    }
    
    return null;
  } catch (error) {
    return null;
  }
};

export default {
  login,
  register,
  getCurrentUser,
  logout,
  updateProfile,
};
