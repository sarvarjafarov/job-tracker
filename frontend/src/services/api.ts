import axios, { AxiosResponse } from 'axios';
import { 
  User, 
  Company, 
  Application, 
  Job, 
  AuthResponse, 
  PaginatedResponse 
} from '../types';

const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor to handle auth errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Auth API
export const authAPI = {
  login: (username: string, password: string): Promise<AxiosResponse<AuthResponse>> =>
    api.post('/login', { username, password }),
  
  register: (data: {
    name: string;
    email: string;
    username: string;
    password: string;
    password_confirmation: string;
  }): Promise<AxiosResponse<AuthResponse>> =>
    api.post('/register', data),
  
  logout: (): Promise<AxiosResponse<{ message: string }>> =>
    api.post('/logout'),
  
  me: (): Promise<AxiosResponse<{ user: User }>> =>
    api.get('/me'),
  
  updateProfile: (data: Partial<User>): Promise<AxiosResponse<{ message: string; user: User }>> =>
    api.put('/profile', data),
  
  changePassword: (data: {
    current_password: string;
    password: string;
    password_confirmation: string;
  }): Promise<AxiosResponse<{ message: string }>> =>
    api.put('/change-password', data),
};

// Applications API
export const applicationsAPI = {
  getAll: (params?: {
    status?: string;
    company_id?: number;
    search?: string;
    page?: number;
  }): Promise<AxiosResponse<PaginatedResponse<Application>>> =>
    api.get('/applications', { params }),
  
  getById: (id: number): Promise<AxiosResponse<Application>> =>
    api.get(`/applications/${id}`),
  
  create: (data: {
    company_id: number;
    job_id?: number;
    status?: string;
    applied_date: string;
    notes?: string;
    resume_url?: string;
    cover_letter_url?: string;
    salary_expectation?: number;
    source?: string;
  }): Promise<AxiosResponse<{ message: string; application: Application }>> =>
    api.post('/applications', data),
  
  update: (id: number, data: Partial<Application>): Promise<AxiosResponse<{ message: string; application: Application }>> =>
    api.put(`/applications/${id}`, data),
  
  delete: (id: number): Promise<AxiosResponse<{ message: string }>> =>
    api.delete(`/applications/${id}`),
};

// Companies API
export const companiesAPI = {
  getAll: (params?: {
    search?: string;
    industry?: string;
    page?: number;
  }): Promise<AxiosResponse<PaginatedResponse<Company>>> =>
    api.get('/companies', { params }),
  
  getById: (id: number): Promise<AxiosResponse<Company>> =>
    api.get(`/companies/${id}`),
  
  create: (data: {
    name: string;
    website?: string;
    email?: string;
    phone?: string;
    address?: string;
    description?: string;
    industry?: string;
    size?: string;
    logo_url?: string;
  }): Promise<AxiosResponse<{ message: string; company: Company }>> =>
    api.post('/companies', data),
  
  update: (id: number, data: Partial<Company>): Promise<AxiosResponse<{ message: string; company: Company }>> =>
    api.put(`/companies/${id}`, data),
  
  delete: (id: number): Promise<AxiosResponse<{ message: string }>> =>
    api.delete(`/companies/${id}`),
};

// Jobs API
export const jobsAPI = {
  getAll: (params?: {
    company_id?: number;
    search?: string;
    page?: number;
  }): Promise<AxiosResponse<PaginatedResponse<Job>>> =>
    api.get('/jobs', { params }),
};

export default api;
