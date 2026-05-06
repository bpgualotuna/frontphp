/**
 * API de Médicos - RTK Query
 * Conectado a la API PHP Backend
 */

import { createApi, fetchBaseQuery } from '@reduxjs/toolkit/query/react';
import { Medico } from '../types';

export const medicosApi = createApi({
  reducerPath: 'medicosApi',
  baseQuery: fetchBaseQuery({
    baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8080/api',
  }),
  tagTypes: ['Medicos'],
  endpoints: (builder) => ({
    // Obtener todos los médicos
    getMedicos: builder.query<Medico[], void>({
      query: () => '/medicos',
      transformResponse: (response: any) => response.data,
      providesTags: ['Medicos'],
    }),
    
    // Obtener médico por ID
    getMedicoById: builder.query<Medico, number | string>({
      query: (id) => `/medicos/${id}`,
      transformResponse: (response: any) => response.data,
      providesTags: ['Medicos'],
    }),
    
    // Obtener médicos por especialidad
    getMedicosByEspecialidad: builder.query<Medico[], string>({
      query: (especialidad) => `/medicos/especialidad/${especialidad}`,
      transformResponse: (response: any) => response.data,
      providesTags: ['Medicos'],
    }),
  }),
});

export const {
  useGetMedicosQuery,
  useGetMedicoByIdQuery,
  useGetMedicosByEspecialidadQuery,
} = medicosApi;
