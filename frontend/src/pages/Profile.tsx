import React, { useState } from 'react';
import { useMutation, useQueryClient } from 'react-query';
import { useAuth } from '../contexts/AuthContext';
import { authAPI } from '../services/api';
import {
  UserIcon,
  EnvelopeIcon,
  KeyIcon,
  CheckCircleIcon,
} from '@heroicons/react/24/outline';

const Profile: React.FC = () => {
  const { user, logout } = useAuth();
  const [activeTab, setActiveTab] = useState<'profile' | 'password'>('profile');
  const [profileData, setProfileData] = useState({
    name: user?.name || '',
    email: user?.email || '',
    username: user?.username || '',
  });
  const [passwordData, setPasswordData] = useState({
    current_password: '',
    password: '',
    password_confirmation: '',
  });
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  
  const queryClient = useQueryClient();

  const updateProfileMutation = useMutation(
    (data: typeof profileData) => authAPI.updateProfile(data),
    {
      onSuccess: (response) => {
        setMessage('Profile updated successfully');
        setError('');
        queryClient.invalidateQueries('me');
      },
      onError: (err: any) => {
        setError(err.response?.data?.message || 'Update failed');
        setMessage('');
      },
    }
  );

  const changePasswordMutation = useMutation(
    (data: typeof passwordData) => authAPI.changePassword(data),
    {
      onSuccess: () => {
        setMessage('Password changed successfully');
        setError('');
        setPasswordData({
          current_password: '',
          password: '',
          password_confirmation: '',
        });
      },
      onError: (err: any) => {
        setError(err.response?.data?.message || 'Password change failed');
        setMessage('');
      },
    }
  );

  const handleProfileSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setMessage('');
    updateProfileMutation.mutate(profileData);
  };

  const handlePasswordSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setMessage('');
    
    if (passwordData.password !== passwordData.password_confirmation) {
      setError('Passwords do not match');
      return;
    }
    
    changePasswordMutation.mutate(passwordData);
  };

  const handleProfileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setProfileData({
      ...profileData,
      [e.target.name]: e.target.value,
    });
  };

  const handlePasswordChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setPasswordData({
      ...passwordData,
      [e.target.name]: e.target.value,
    });
  };

  return (
    <div className="p-6">
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900">Profile Settings</h1>
        <p className="text-gray-600 mt-2">Manage your account settings and preferences</p>
      </div>

      <div className="max-w-2xl">
        {/* Tabs */}
        <div className="border-b border-gray-200 mb-6">
          <nav className="-mb-px flex space-x-8">
            <button
              onClick={() => setActiveTab('profile')}
              className={`py-2 px-1 border-b-2 font-medium text-sm ${
                activeTab === 'profile'
                  ? 'border-primary-500 text-primary-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              }`}
            >
              Profile Information
            </button>
            <button
              onClick={() => setActiveTab('password')}
              className={`py-2 px-1 border-b-2 font-medium text-sm ${
                activeTab === 'password'
                  ? 'border-primary-500 text-primary-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              }`}
            >
              Change Password
            </button>
          </nav>
        </div>

        {/* Messages */}
        {message && (
          <div className="mb-6 bg-success-50 border border-success-200 text-success-600 px-4 py-3 rounded-md flex items-center">
            <CheckCircleIcon className="h-5 w-5 mr-2" />
            {message}
          </div>
        )}

        {error && (
          <div className="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
            {error}
          </div>
        )}

        {/* Profile Information Tab */}
        {activeTab === 'profile' && (
          <div className="card">
            <div className="flex items-center mb-6">
              <div className="flex-shrink-0">
                <div className="h-16 w-16 rounded-full bg-primary-600 flex items-center justify-center">
                  <UserIcon className="h-8 w-8 text-white" />
                </div>
              </div>
              <div className="ml-4">
                <h2 className="text-lg font-semibold text-gray-900">{user?.name}</h2>
                <p className="text-sm text-gray-500">{user?.role}</p>
              </div>
            </div>

            <form onSubmit={handleProfileSubmit} className="space-y-6">
              <div>
                <label htmlFor="name" className="block text-sm font-medium text-gray-700">
                  Full Name
                </label>
                <input
                  type="text"
                  name="name"
                  id="name"
                  className="input mt-1"
                  value={profileData.name}
                  onChange={handleProfileChange}
                  required
                />
              </div>

              <div>
                <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                  Email Address
                </label>
                <input
                  type="email"
                  name="email"
                  id="email"
                  className="input mt-1"
                  value={profileData.email}
                  onChange={handleProfileChange}
                  required
                />
              </div>

              <div>
                <label htmlFor="username" className="block text-sm font-medium text-gray-700">
                  Username
                </label>
                <input
                  type="text"
                  name="username"
                  id="username"
                  className="input mt-1"
                  value={profileData.username}
                  onChange={handleProfileChange}
                  required
                />
              </div>

              <div className="flex justify-end">
                <button
                  type="submit"
                  disabled={updateProfileMutation.isLoading}
                  className="btn btn-primary"
                >
                  {updateProfileMutation.isLoading ? 'Updating...' : 'Update Profile'}
                </button>
              </div>
            </form>
          </div>
        )}

        {/* Change Password Tab */}
        {activeTab === 'password' && (
          <div className="card">
            <div className="flex items-center mb-6">
              <KeyIcon className="h-8 w-8 text-gray-400 mr-3" />
              <div>
                <h2 className="text-lg font-semibold text-gray-900">Change Password</h2>
                <p className="text-sm text-gray-500">Update your password to keep your account secure</p>
              </div>
            </div>

            <form onSubmit={handlePasswordSubmit} className="space-y-6">
              <div>
                <label htmlFor="current_password" className="block text-sm font-medium text-gray-700">
                  Current Password
                </label>
                <input
                  type="password"
                  name="current_password"
                  id="current_password"
                  className="input mt-1"
                  value={passwordData.current_password}
                  onChange={handlePasswordChange}
                  required
                />
              </div>

              <div>
                <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                  New Password
                </label>
                <input
                  type="password"
                  name="password"
                  id="password"
                  className="input mt-1"
                  value={passwordData.password}
                  onChange={handlePasswordChange}
                  required
                  minLength={8}
                />
              </div>

              <div>
                <label htmlFor="password_confirmation" className="block text-sm font-medium text-gray-700">
                  Confirm New Password
                </label>
                <input
                  type="password"
                  name="password_confirmation"
                  id="password_confirmation"
                  className="input mt-1"
                  value={passwordData.password_confirmation}
                  onChange={handlePasswordChange}
                  required
                  minLength={8}
                />
              </div>

              <div className="flex justify-end">
                <button
                  type="submit"
                  disabled={changePasswordMutation.isLoading}
                  className="btn btn-primary"
                >
                  {changePasswordMutation.isLoading ? 'Changing...' : 'Change Password'}
                </button>
              </div>
            </form>
          </div>
        )}
      </div>
    </div>
  );
};

export default Profile;
