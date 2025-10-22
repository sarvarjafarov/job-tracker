import React from 'react';
import { useParams } from 'react-router-dom';

const ApplicationDetail: React.FC = () => {
  const { id } = useParams<{ id: string }>();

  return (
    <div className="p-6">
      <h1 className="text-3xl font-bold text-gray-900 mb-6">Application Details</h1>
      <div className="card">
        <p className="text-gray-600">Application ID: {id}</p>
        <p className="text-sm text-gray-500 mt-2">This page will show detailed application information.</p>
      </div>
    </div>
  );
};

export default ApplicationDetail;
