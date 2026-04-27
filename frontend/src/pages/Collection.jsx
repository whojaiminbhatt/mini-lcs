import React, { useState } from 'react';
import api from '../api';
import { IndianRupee } from 'lucide-react';

const Collection = () => {
  const [formData, setFormData] = useState({
    loan_no: '',
    amount_paid: '',
    payment_mode: 'cash',
    location: '',
    collected_at: new Date().toISOString().slice(0, 16)
  });
  
  const [status, setStatus] = useState({ type: '', message: '' });
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setStatus({ type: '', message: '' });

    try {
      await api.post('/collections', formData);
      setStatus({ type: 'success', message: 'Collection added successfully!' });
      setFormData({
        loan_no: '',
        amount_paid: '',
        payment_mode: 'cash',
        location: '',
        collected_at: new Date().toISOString().slice(0, 16)
      });
    } catch (error) {
      const errData = error.response?.data;
      let msg = errData?.message || 'Failed to add collection. Please check details.';
      if (errData?.errors) {
        msg += ' ' + Object.values(errData.errors).flat().join('. ');
      }
      setStatus({ type: 'error', message: msg });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{ maxWidth: '600px', margin: '0 auto' }}>
      <h1 style={{ marginBottom: '24px' }}>Add Collection Entry</h1>

      <div className="glass-panel">
        <div style={{ display: 'flex', alignItems: 'center', gap: '12px', marginBottom: '24px', paddingBottom: '16px', borderBottom: '1px solid var(--border-color)' }}>
          <div style={{ padding: '12px', background: 'rgba(16, 185, 129, 0.2)', borderRadius: '12px', color: 'var(--success)' }}>
            <IndianRupee size={24} />
          </div>
          <div>
            <h3 style={{ margin: 0 }}>Record Payment</h3>
            <p className="text-muted" style={{ fontSize: '14px', margin: 0 }}>Ensure amount does not exceed pending balance</p>
          </div>
        </div>

        {status.message && (
          <div style={{ 
            padding: '12px', 
            borderRadius: '8px', 
            marginBottom: '20px', 
            fontSize: '14px',
            background: status.type === 'success' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)',
            color: status.type === 'success' ? 'var(--success)' : 'var(--danger)',
            border: `1px solid ${status.type === 'success' ? 'var(--success)' : 'var(--danger)'}`
          }}>
            {status.message}
          </div>
        )}

        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label className="form-label">Loan Number</label>
            <input 
              required 
              type="text" 
              className="form-control" 
              placeholder="Enter loan number (e.g., LN001)"
              value={formData.loan_no} 
              onChange={e => setFormData({...formData, loan_no: e.target.value})} 
            />
          </div>

          <div style={{ display: 'flex', gap: '16px' }}>
            <div className="form-group" style={{ flex: 1 }}>
              <label className="form-label">Amount Paid (₹)</label>
              <input 
                required 
                type="number" 
                className="form-control" 
                placeholder="0.00"
                min="1"
                value={formData.amount_paid} 
                onChange={e => setFormData({...formData, amount_paid: e.target.value})} 
              />
            </div>
            
            <div className="form-group" style={{ flex: 1 }}>
              <label className="form-label">Payment Mode</label>
              <select 
                className="form-control" 
                value={formData.payment_mode} 
                onChange={e => setFormData({...formData, payment_mode: e.target.value})}
              >
                <option value="cash">Cash</option>
                <option value="upi">UPI</option>
                <option value="card">Card</option>
              </select>
            </div>
          </div>

          <div className="form-group">
            <label className="form-label">Collection Date & Time</label>
            <input 
              required 
              type="datetime-local" 
              className="form-control" 
              value={formData.collected_at} 
              onChange={e => setFormData({...formData, collected_at: e.target.value})} 
            />
          </div>

          <div className="form-group">
            <label className="form-label">Location (Optional)</label>
            <input 
              type="text" 
              className="form-control" 
              placeholder="E.g., Customer Home"
              value={formData.location} 
              onChange={e => setFormData({...formData, location: e.target.value})} 
            />
          </div>

          <button type="submit" className="btn btn-primary" style={{ width: '100%', marginTop: '16px' }} disabled={loading}>
            {loading ? 'Processing...' : 'Submit Collection'}
          </button>
        </form>
      </div>
    </div>
  );
};

export default Collection;
