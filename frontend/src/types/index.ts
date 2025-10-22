export interface User {
  id: number;
  name: string;
  email: string;
  username: string;
  role: 'super_admin' | 'admin' | 'user';
  is_active: boolean;
  created_at: string;
  updated_at: string;
}

export interface Company {
  id: number;
  name: string;
  website?: string;
  email?: string;
  phone?: string;
  address?: string;
  description?: string;
  industry?: string;
  size?: string;
  logo_url?: string;
  created_at: string;
  updated_at: string;
}

export interface Job {
  id: number;
  company_id: number;
  title: string;
  description?: string;
  location?: string;
  salary_min?: number;
  salary_max?: number;
  employment_type: 'full-time' | 'part-time' | 'contract' | 'internship' | 'freelance';
  experience_level: 'entry' | 'mid' | 'senior' | 'lead' | 'executive';
  remote_option: boolean;
  job_url?: string;
  posted_date?: string;
  application_deadline?: string;
  company?: Company;
  created_at: string;
  updated_at: string;
}

export interface Application {
  id: number;
  user_id: number;
  company_id: number;
  job_id?: number;
  status: ApplicationStatus;
  applied_date: string;
  notes?: string;
  resume_url?: string;
  cover_letter_url?: string;
  salary_expectation?: number;
  source?: string;
  company?: Company;
  job?: Job;
  interviews?: Interview[];
  application_notes?: ApplicationNote[];
  created_at: string;
  updated_at: string;
}

export type ApplicationStatus = 
  | 'applied'
  | 'under_review'
  | 'phone_screening'
  | 'interview_scheduled'
  | 'interviewed'
  | 'technical_interview'
  | 'final_interview'
  | 'offer_received'
  | 'offer_accepted'
  | 'offer_declined'
  | 'rejected'
  | 'withdrawn';

export interface Interview {
  id: number;
  application_id: number;
  interview_date: string;
  interview_time: string;
  type: 'phone' | 'video' | 'in-person' | 'technical' | 'hr' | 'final';
  location?: string;
  interviewer_name?: string;
  interviewer_email?: string;
  notes?: string;
  status: 'scheduled' | 'completed' | 'cancelled' | 'rescheduled';
  feedback?: string;
  created_at: string;
  updated_at: string;
}

export interface ApplicationNote {
  id: number;
  application_id: number;
  user_id: number;
  note: string;
  is_private: boolean;
  user?: User;
  created_at: string;
  updated_at: string;
}

export interface AuthResponse {
  message: string;
  user: User;
  token: string;
}

export interface ApiResponse<T> {
  data: T;
  message?: string;
}

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number;
  to: number;
}
