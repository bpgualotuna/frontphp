/**
 * API de Terapias - RTK Query
 * Conectado a la API PHP Backend
 */

import { createApi, fetchBaseQuery } from '@reduxjs/toolkit/query/react';
import { Terapia } from '../types';

export const terapiasApi = createApi({
  reducerPath: 'terapiasApi',
  baseQuery: fetchBaseQuery({
    baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8080/api',
  }),
  tagTypes: ['Terapias'],
  endpoints: (builder) => ({
    // Obtener todas las terapias activas
    getTerapias: builder.query<Terapia[], void>({
      query: () => '/terapias',
      transformResponse: (response: any) => response.data,
      providesTags: ['Terapias'],
    }),
    
    // Obtener terapia por ID
    getTerapiaById: builder.query<Terapia, number | string>({
      query: (id) => `/terapias/${id}`,
      transformResponse: (response: any) => response.data,
      providesTags: ['Terapias'],
    }),
  }),
});

export const {
  useGetTerapiasQuery,
  useGetTerapiaByIdQuery,
} = terapiasApi;
