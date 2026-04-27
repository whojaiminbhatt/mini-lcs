import React, { useState, useEffect } from 'react';
import api from '../api';
import { PieChart, Pie, Cell, ResponsiveContainer, Tooltip, Legend } from 'recharts';
import { IndianRupee, Users, Clock, AlertCircle } from 'lucide-react';

const Dashboard = () => {
  const [metrics, setMetrics] = useState(null);
  const [bestTime, setBestTime] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchDashboardData = async () => {
      try {
        const [metricsRes, bestTimeRes] = await Promise.all([
          api.get('/dashboard'),
          api.get('/dashboard/best-time')
        ]);
        setMetrics(metricsRes.data.data);
        setBestTime(bestTimeRes.data.data);
      } catch (error) {
        console.error('Error fetching dashboard data:', error);
      } finally {
        setLoading(false);
      }
    };
    fetchDashboardData();
  }, []);

  if (loading) return <div>Loading dashboard...</div>;

  const COLORS = ['#4F46E5', '#10B981', '#F59E0B'];
  const chartData = (Array.isArray(metrics?.collection_by_mode) ? metrics.collection_by_mode : []).map(item => ({
    name: item.payment_mode?.toUpperCase() ?? 'UNKNOWN',
    value: parseFloat(item.total) || 0
  }));

  const StatCard = ({ title, value, icon: Icon, color }) => (
    <div className="glass-panel" style={{ flex: 1, minWidth: '200px' }}>
      <div style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
        <div style={{ padding: '12px', background: `rgba(${color}, 0.2)`, borderRadius: '12px', color: `rgb(${color})` }}>
          <Icon size={24} />
        </div>
        <div>
          <p className="text-muted" style={{ fontSize: '14px', marginBottom: '4px' }}>{title}</p>
          <h3 style={{ margin: 0, fontSize: '24px' }}>{value}</h3>
        </div>
      </div>
    </div>
  );

  return (
    <div>
      <h1 style={{ marginBottom: '24px' }}>Dashboard Overview</h1>
      
      <div style={{ display: 'flex', gap: '24px', flexWrap: 'wrap', marginBottom: '32px' }}>
        <StatCard title="Total Loans" value={metrics?.total_loans || 0} icon={Users} color="79, 70, 229" />
        <StatCard title="Collected Today" value={`₹${metrics?.total_collected_today || 0}`} icon={IndianRupee} color="16, 185, 129" />
        <StatCard title="Total Pending" value={`₹${metrics?.pending_amount || 0}`} icon={AlertCircle} color="245, 158, 11" />
      </div>

      <div style={{ display: 'flex', gap: '24px', flexWrap: 'wrap' }}>
        <div className="glass-panel" style={{ flex: 2, minWidth: '300px' }}>
          <h3>Collection by Payment Mode</h3>
          {chartData.length > 0 ? (
            <div style={{ height: '300px' }}>
              <ResponsiveContainer width="100%" height="100%">
                <PieChart>
                  <Pie
                    data={chartData}
                    cx="50%"
                    cy="50%"
                    innerRadius={60}
                    outerRadius={100}
                    paddingAngle={5}
                    dataKey="value"
                  >
                    {chartData.map((entry, index) => (
                      <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                    ))}
                  </Pie>
                  <Tooltip formatter={(value) => `₹${value}`} />
                  <Legend />
                </PieChart>
              </ResponsiveContainer>
            </div>
          ) : (
            <p className="text-muted">No collection data available.</p>
          )}
        </div>

        <div className="glass-panel" style={{ flex: 1, minWidth: '300px' }}>
          <h3>Prediction Insights</h3>
          <p className="text-muted" style={{ marginBottom: '24px', fontSize: '14px' }}>
            Based on historical data, here is the best time slot for field agents to collect payments.
          </p>
          
          {bestTime?.best_slot ? (
            <div style={{ background: 'rgba(79, 70, 229, 0.1)', padding: '24px', borderRadius: '12px', textAlign: 'center', border: '1px solid rgba(79, 70, 229, 0.3)' }}>
              <Clock size={32} color="var(--primary-color)" style={{ marginBottom: '12px' }} />
              <h2 style={{ color: 'var(--primary-color)', margin: '0 0 8px 0' }}>{bestTime.best_slot}</h2>
              <p className="text-muted" style={{ fontSize: '14px' }}>Highest historical collection volume</p>
            </div>
          ) : (
            <p className="text-muted">Not enough data for prediction.</p>
          )}
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
