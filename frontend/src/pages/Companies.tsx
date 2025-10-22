import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from 'react-query';
import { Link } from 'react-router-dom';
import { companiesAPI } from '../services/api';
import { Company } from '../types';
import {
  PlusIcon,
  MagnifyingGlassIcon,
  BuildingOfficeIcon,
  PencilIcon,
  TrashIcon,
  GlobeAltIcon,
  EnvelopeIcon,
  PhoneIcon,
} from '@heroicons/react/24/outline';

const Companies: React.FC = () => {
  const [search, setSearch] = useState('');
  const [industryFilter, setIndustryFilter] = useState('');
  const [showAddModal, setShowAddModal] = useState(false);
  
  const queryClient = useQueryClient();

  const { data: companies, isLoading } = useQuery(
    ['companies', { search, industry: industryFilter }],
    () => companiesAPI.getAll({ search, industry: industryFilter }).then(res => res.data),
    {
      select: (data) => data.data,
    }
  );

  const deleteMutation = useMutation(
    (id: number) => companiesAPI.delete(id),
    {
      onSuccess: () => {
        queryClient.invalidateQueries('companies');
      },
    }
  );

  const handleDelete = (id: number) => {
    if (window.confirm('Are you sure you want to delete this company?')) {
      deleteMutation.mutate(id);
    }
  };

  if (isLoading) {
    return (
      <div className="p-6">
        <div className="animate-pulse">
          <div className="h-8 bg-gray-200 rounded w-1/4 mb-6"></div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {[...Array(6)].map((_, i) => (
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
        <h1 className="text-3xl font-bold text-gray-900">Companies</h1>
        <button
          onClick={() => setShowAddModal(true)}
          className="btn btn-primary"
        >
          <PlusIcon className="h-5 w-5 mr-2" />
          Add Company
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
                placeholder="Search companies..."
                className="input pl-10"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
            </div>
          </div>
          <div className="sm:w-48">
            <select
              className="input"
              value={industryFilter}
              onChange={(e) => setIndustryFilter(e.target.value)}
            >
              <option value="">All Industries</option>
              <option value="Technology">Technology</option>
              <option value="Finance">Finance</option>
              <option value="Healthcare">Healthcare</option>
              <option value="Education">Education</option>
              <option value="Retail">Retail</option>
              <option value="Manufacturing">Manufacturing</option>
              <option value="Other">Other</option>
            </select>
          </div>
        </div>
      </div>

      {/* Companies Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {companies && companies.length > 0 ? (
          companies.map((company) => (
            <div key={company.id} className="card">
              <div className="flex items-start justify-between mb-4">
                <div className="flex items-center space-x-3">
                  <div className="flex-shrink-0">
                    {company.logo_url ? (
                      <img
                        src={company.logo_url}
                        alt={company.name}
                        className="h-10 w-10 rounded-full object-cover"
                      />
                    ) : (
                      <div className="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                        <BuildingOfficeIcon className="h-6 w-6 text-primary-600" />
                      </div>
                    )}
                  </div>
                  <div>
                    <h3 className="text-lg font-semibold text-gray-900">{company.name}</h3>
                    {company.industry && (
                      <p className="text-sm text-gray-500">{company.industry}</p>
                    )}
                  </div>
                </div>
                <div className="flex space-x-2">
                  <Link
                    to={`/companies/${company.id}`}
                    className="p-2 text-gray-400 hover:text-primary-600"
                  >
                    <PencilIcon className="h-5 w-5" />
                  </Link>
                  <button
                    onClick={() => handleDelete(company.id)}
                    className="p-2 text-gray-400 hover:text-red-600"
                  >
                    <TrashIcon className="h-5 w-5" />
                  </button>
                </div>
              </div>

              {company.description && (
                <p className="text-sm text-gray-600 mb-4 line-clamp-2">
                  {company.description}
                </p>
              )}

              <div className="space-y-2">
                {company.website && (
                  <div className="flex items-center text-sm text-gray-600">
                    <GlobeAltIcon className="h-4 w-4 mr-2" />
                    <a
                      href={company.website}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="hover:text-primary-600"
                    >
                      {company.website}
                    </a>
                  </div>
                )}
                {company.email && (
                  <div className="flex items-center text-sm text-gray-600">
                    <EnvelopeIcon className="h-4 w-4 mr-2" />
                    <a
                      href={`mailto:${company.email}`}
                      className="hover:text-primary-600"
                    >
                      {company.email}
                    </a>
                  </div>
                )}
                {company.phone && (
                  <div className="flex items-center text-sm text-gray-600">
                    <PhoneIcon className="h-4 w-4 mr-2" />
                    <a
                      href={`tel:${company.phone}`}
                      className="hover:text-primary-600"
                    >
                      {company.phone}
                    </a>
                  </div>
                )}
              </div>

              <div className="mt-4 pt-4 border-t border-gray-200">
                <Link
                  to={`/companies/${company.id}`}
                  className="text-primary-600 hover:text-primary-700 text-sm font-medium"
                >
                  View details →
                </Link>
              </div>
            </div>
          ))
        ) : (
          <div className="col-span-full text-center py-12">
            <div className="mx-auto h-12 w-12 text-gray-400">
              <BuildingOfficeIcon className="h-12 w-12" />
            </div>
            <h3 className="mt-2 text-sm font-medium text-gray-900">No companies</h3>
            <p className="mt-1 text-sm text-gray-500">Get started by adding your first company.</p>
            <div className="mt-6">
              <button
                onClick={() => setShowAddModal(true)}
                className="btn btn-primary"
              >
                <PlusIcon className="h-5 w-5 mr-2" />
                Add Company
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default Companies;
