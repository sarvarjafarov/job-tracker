import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from 'react-query';
import { Link } from 'react-router-dom';
import { applicationsAPI } from '../services/api';
import { Application, ApplicationStatus } from '../types';
import {
  PlusIcon,
  MagnifyingGlassIcon,
  FunnelIcon,
  PencilIcon,
  TrashIcon,
} from '@heroicons/react/24/outline';

const Applications: React.FC = () => {
  const [search, setSearch] = useState('');
  const [statusFilter, setStatusFilter] = useState<ApplicationStatus | ''>('');
  const [showAddModal, setShowAddModal] = useState(false);
  
  const queryClient = useQueryClient();

  const { data: applications, isLoading } = useQuery(
    ['applications', { search, status: statusFilter }],
    () => applicationsAPI.getAll({ search, status: statusFilter }).then(res => res.data),
    {
      select: (data) => data.data,
    }
  );

  const deleteMutation = useMutation(
    (id: number) => applicationsAPI.delete(id),
    {
      onSuccess: () => {
        queryClient.invalidateQueries('applications');
      },
    }
  );

  const handleDelete = (id: number) => {
    if (window.confirm('Are you sure you want to delete this application?')) {
      deleteMutation.mutate(id);
    }
  };

  const getStatusColor = (status: ApplicationStatus) => {
    const colors = {
      applied: 'status-applied',
      under_review: 'status-under-review',
      phone_screening: 'status-under-review',
      interview_scheduled: 'status-interview-scheduled',
      interviewed: 'status-interviewed',
      technical_interview: 'status-interviewed',
      final_interview: 'status-interviewed',
      offer_received: 'status-offer-received',
      offer_accepted: 'status-offer-received',
      offer_declined: 'status-rejected',
      rejected: 'status-rejected',
      withdrawn: 'status-rejected',
    };
    return colors[status] || 'status-applied';
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString();
  };

  if (isLoading) {
    return (
      <div className="p-6">
        <div className="animate-pulse">
          <div className="h-8 bg-gray-200 rounded w-1/4 mb-6"></div>
          <div className="space-y-4">
            {[...Array(5)].map((_, i) => (
              <div key={i} className="bg-white p-6 rounded-lg shadow">
                <div className="h-4 bg-gray-200 rounded w-1/2 mb-2"></div>
                <div className="h-4 bg-gray-200 rounded w-1/3"></div>
              </div>
            ))}
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold text-gray-900">Applications</h1>
        <button
          onClick={() => setShowAddModal(true)}
          className="btn btn-primary"
        >
          <PlusIcon className="h-5 w-5 mr-2" />
          Add Application
        </button>
      </div>

      {/* Filters */}
      <div className="bg-white p-4 rounded-lg shadow mb-6">
        <div className="flex flex-col sm:flex-row gap-4">
          <div className="flex-1">
            <div className="relative">
              <MagnifyingGlassIcon className="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
              <input
                type="text"
                placeholder="Search applications..."
                className="input pl-10"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
            </div>
          </div>
          <div className="sm:w-48">
            <select
              className="input"
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value as ApplicationStatus | '')}
            >
              <option value="">All Statuses</option>
              <option value="applied">Applied</option>
              <option value="under_review">Under Review</option>
              <option value="phone_screening">Phone Screening</option>
              <option value="interview_scheduled">Interview Scheduled</option>
              <option value="interviewed">Interviewed</option>
              <option value="technical_interview">Technical Interview</option>
              <option value="final_interview">Final Interview</option>
              <option value="offer_received">Offer Received</option>
              <option value="offer_accepted">Offer Accepted</option>
              <option value="offer_declined">Offer Declined</option>
              <option value="rejected">Rejected</option>
              <option value="withdrawn">Withdrawn</option>
            </select>
          </div>
        </div>
      </div>

      {/* Applications List */}
      <div className="space-y-4">
        {applications && applications.length > 0 ? (
          applications.map((application) => (
            <div key={application.id} className="card">
              <div className="flex items-center justify-between">
                <div className="flex-1">
                  <div className="flex items-center space-x-4">
                    <div>
                      <h3 className="text-lg font-semibold text-gray-900">
                        {application.company?.name}
                      </h3>
                      <p className="text-gray-600">
                        {application.job?.title || 'No specific job'}
                      </p>
                      <p className="text-sm text-gray-500">
                        Applied on {formatDate(application.applied_date)}
                      </p>
                    </div>
                  </div>
                </div>
                <div className="flex items-center space-x-4">
                  <span className={`status-badge ${getStatusColor(application.status)}`}>
                    {application.status.replace('_', ' ')}
                  </span>
                  <div className="flex space-x-2">
                    <Link
                      to={`/applications/${application.id}`}
                      className="p-2 text-gray-400 hover:text-primary-600"
                    >
                      <PencilIcon className="h-5 w-5" />
                    </Link>
                    <button
                      onClick={() => handleDelete(application.id)}
                      className="p-2 text-gray-400 hover:text-red-600"
                    >
                      <TrashIcon className="h-5 w-5" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          ))
        ) : (
          <div className="text-center py-12">
            <div className="mx-auto h-12 w-12 text-gray-400">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <h3 className="mt-2 text-sm font-medium text-gray-900">No applications</h3>
            <p className="mt-1 text-sm text-gray-500">Get started by adding your first application.</p>
            <div className="mt-6">
              <button
                onClick={() => setShowAddModal(true)}
                className="btn btn-primary"
              >
                <PlusIcon className="h-5 w-5 mr-2" />
                Add Application
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default Applications;
