import React from 'react';
import { useQuery } from 'react-query';
import { Link } from 'react-router-dom';
import { applicationsAPI } from '../services/api';
import {
  BriefcaseIcon,
  BuildingOfficeIcon,
  CheckCircleIcon,
  ClockIcon,
  XCircleIcon,
} from '@heroicons/react/24/outline';

const Dashboard: React.FC = () => {
  const { data: applications, isLoading } = useQuery(
    'applications',
    () => applicationsAPI.getAll().then(res => res.data),
    {
      select: (data) => data.data,
    }
  );

  const stats = React.useMemo(() => {
    if (!applications) return null;

    const total = applications.length;
    const applied = applications.filter(app => app.status === 'applied').length;
    const interviewed = applications.filter(app => 
      ['interview_scheduled', 'interviewed', 'technical_interview', 'final_interview'].includes(app.status)
    ).length;
    const offers = applications.filter(app => 
      ['offer_received', 'offer_accepted'].includes(app.status)
    ).length;
    const rejected = applications.filter(app => app.status === 'rejected').length;

    return { total, applied, interviewed, offers, rejected };
  }, [applications]);

  const recentApplications = applications?.slice(0, 5) || [];

  if (isLoading) {
    return (
      <div className="p-6">
        <div className="animate-pulse">
          <div className="h-8 bg-gray-200 rounded w-1/4 mb-6"></div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {[...Array(4)].map((_, i) => (
              <div key={i} className="bg-white p-6 rounded-lg shadow">
                <div className="h-4 bg-gray-200 rounded w-1/2 mb-2"></div>
                <div className="h-8 bg-gray-200 rounded w-1/3"></div>
              </div>
            ))}
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="p-6">
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p className="text-gray-600 mt-2">Track your job applications and progress</p>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div className="card">
          <div className="flex items-center">
            <div className="flex-shrink-0">
              <BriefcaseIcon className="h-8 w-8 text-primary-600" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-500">Total Applications</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.total || 0}</p>
            </div>
          </div>
        </div>

        <div className="card">
          <div className="flex items-center">
            <div className="flex-shrink-0">
              <ClockIcon className="h-8 w-8 text-warning-600" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-500">Applied</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.applied || 0}</p>
            </div>
          </div>
        </div>

        <div className="card">
          <div className="flex items-center">
            <div className="flex-shrink-0">
              <BuildingOfficeIcon className="h-8 w-8 text-purple-600" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-500">Interviewed</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.interviewed || 0}</p>
            </div>
          </div>
        </div>

        <div className="card">
          <div className="flex items-center">
            <div className="flex-shrink-0">
              <CheckCircleIcon className="h-8 w-8 text-success-600" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-500">Offers</p>
              <p className="text-2xl font-bold text-gray-900">{stats?.offers || 0}</p>
            </div>
          </div>
        </div>
      </div>

      {/* Recent Applications */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="card">
          <div className="flex items-center justify-between mb-4">
            <h2 className="text-lg font-semibold text-gray-900">Recent Applications</h2>
            <Link
              to="/applications"
              className="text-primary-600 hover:text-primary-700 text-sm font-medium"
            >
              View all
            </Link>
          </div>
          <div className="space-y-3">
            {recentApplications.length > 0 ? (
              recentApplications.map((application) => (
                <div key={application.id} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div>
                    <p className="font-medium text-gray-900">{application.company?.name}</p>
                    <p className="text-sm text-gray-500">{application.job?.title || 'No specific job'}</p>
                  </div>
                  <span className={`status-badge status-${application.status.replace('_', '-')}`}>
                    {application.status.replace('_', ' ')}
                  </span>
                </div>
              ))
            ) : (
              <div className="text-center py-8">
                <BriefcaseIcon className="h-12 w-12 text-gray-400 mx-auto mb-4" />
                <p className="text-gray-500">No applications yet</p>
                <Link
                  to="/applications"
                  className="text-primary-600 hover:text-primary-700 text-sm font-medium"
                >
                  Add your first application
                </Link>
              </div>
            )}
          </div>
        </div>

        <div className="card">
          <h2 className="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
          <div className="space-y-3">
            <Link
              to="/applications"
              className="block w-full text-left p-3 bg-primary-50 hover:bg-primary-100 rounded-lg transition-colors"
            >
              <div className="flex items-center">
                <BriefcaseIcon className="h-5 w-5 text-primary-600 mr-3" />
                <span className="font-medium text-primary-900">Add New Application</span>
              </div>
            </Link>
            <Link
              to="/companies"
              className="block w-full text-left p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors"
            >
              <div className="flex items-center">
                <BuildingOfficeIcon className="h-5 w-5 text-gray-600 mr-3" />
                <span className="font-medium text-gray-900">Manage Companies</span>
              </div>
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
