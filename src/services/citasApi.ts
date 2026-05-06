/**
 * API de Citas - RTK Query
 * Conectado a la API PHP Backend
 */

import { createApi, fetchBaseQuery } from '@reduxjs/toolkit/query/react';
import { Cita, AppointmentFormData, HorarioDisponible } from '../types';
import { AUTH_TOKEN_KEY } from '../app/axiosClient';

export const citasApi = createApi({
  reducerPath: 'citasApi',
  baseQuery: fetchBaseQuery({
    baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8080/api',
    prepareHeaders: (headers) => {
      const token = sessionStorage.getItem(AUTH_TOKEN_KEY);
      if (token) {
        headers.set('Authorization', `Bearer ${token}`);
      }
      return headers;
    },
  }),
  tagTypes: ['Citas'],
  endpoints: (builder) => ({
    // Obtener todas las citas del paciente
    getCitasPaciente: builder.query<Cita[], number | string>({
      query: (pacienteId) => `/citas/paciente/${pacienteId}`,
      transformResponse: (response: any) => response.data,
      providesTags: ['Citas'],
    }),
    
    // Obtener próximas citas del paciente
    getProximasCitas: builder.query<Cita[], number | string>({
      query: (pacienteId) => `/citas/proximas/${pacienteId}`,
      transformResponse: (response: any) => response.data,
      providesTags: ['Citas'],
    }),
    
    // Obtener horarios disponibles
    getHorariosDisponibles: builder.query<HorarioDisponible[], { terapiaId: number | string; fecha: string }>({
      query: ({ terapiaId, fecha }) => `/horarios/disponibles?fecha=${fecha}&terapiaId=${terapiaId}`,
      transformResponse: (response: any) => response.data,
    }),
    
    // Crear nueva cita
    createCita: builder.mutation<Cita, { pacienteId: number | string; data: AppointmentFormData }>({
      query: ({ pacienteId, data }) => ({
        url: '/citas',
        method: 'POST',
        body: {
          pacienteId,
          medicoId: data.medicoId,
          terapiaId: data.terapiaId,
          fecha: data.fecha,
          hora: data.hora,
          sintomas: data.sintomas,
          tieneExamenes: data.tieneExamenes,
          examenes: data.examenes || null,
        },
      }),
      transformResponse: (response: any) => response.data,
      invalidatesTags: ['Citas'],
    }),
    
    // Cancelar cita
    cancelarCita: builder.mutation<Cita, { citaId: number | string; motivo: string }>({
      query: ({ citaId, motivo }) => ({
        url: `/citas/${citaId}/cancelar`,
        method: 'PUT',
        body: { motivo },
      }),
      transformResponse: (response: any) => response.data,
      invalidatesTags: ['Citas'],
    }),
  }),
});

export const {
  useGetCitasPacienteQuery,
  useGetProximasCitasQuery,
  useGetHorariosDisponiblesQuery,
  useCreateCitaMutation,
  useCancelarCitaMutation,
} = citasApi;
