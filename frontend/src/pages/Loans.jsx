import React, { useState, useEffect, useContext } from 'react';
import api from '../api';
import { AuthContext } from '../AuthContext';
import { Search } from 'lucide-react';

const Loans = () => {
  const [loans, setLoans] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchNo, setSearchNo] = useState('');
  const [searchName, setSearchName] = useState('');
  const { user } = useContext(AuthContext);
  
  // New Loan Form State
  const [showModal, setShowModal] = useState(false);
  const [formData, setFormData] = useState({
    loan_no: '',
    customer_name: '',
    mobile: '',
    address: '',
    total_amount: '',
    emi_amount: ''
  });

  const fetchLoans = async () => {
    setLoading(true);
    try {
      const params = {};
      if (searchNo) params.loan_no = searchNo;
      if (searchName) params.customer_name = searchName;
      
      const response = await api.get('/loans', { params });
      setLoans(response.data.data.data); // Unwrap: axios.data -> Responder.data -> Paginator.data
    } catch (error) {
      console.error('Error fetching loans:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchLoans();
  }, []); // Initial fetch

  const handleSearch = (e) => {
    e.preventDefault();
    fetchLoans();
  };

  const handleCreateLoan = async (e) => {
    e.preventDefault();
    try {
      await api.post('/loans', formData);
      setShowModal(false);
      setFormData({ loan_no: '', customer_name: '', mobile: '', address: '', total_amount: '', emi_amount: '' });
      fetchLoans();
    } catch (error) {
      const errData = error.response?.data;
      let msg = errData?.message || error.message;
      if (errData?.errors) {
        msg += '\n' + Object.values(errData.errors).flat().join('\n');
      }
      alert('Error creating loan: ' + msg);
    }
  };

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <h1>Loan Management</h1>
        {user?.role === 'admin' && (
          <button className="btn btn-primary" onClick={() => setShowModal(true)}>
            + Create New Loan
          </button>
        )}
      </div>

      <div className="glass-panel" style={{ marginBottom: '24px' }}>
        <form onSubmit={handleSearch} style={{ display: 'flex', gap: '16px', alignItems: 'flex-end' }}>
          <div className="form-group" style={{ margin: 0, flex: 1 }}>
            <label className="form-label">Loan Number</label>
            <input 
              type="text" 
              className="form-control" 
              placeholder="e.g. LN001"
              value={searchNo}
              onChange={(e) => setSearchNo(e.target.value)}
            />
          </div>
          <div className="form-group" style={{ margin: 0, flex: 1 }}>
            <label className="form-label">Customer Name</label>
            <input 
              type="text" 
              className="form-control" 
              placeholder="e.g. John"
              value={searchName}
              onChange={(e) => setSearchName(e.target.value)}
            />
          </div>
          <button type="submit" className="btn btn-primary" style={{ padding: '12px' }}>
            <Search size={20} />
          </button>
        </form>
      </div>

      <div className="glass-panel">
        <div className="table-wrapper">
          {loading ? (
            <p>Loading loans...</p>
          ) : (
            <table>
              <thead>
                <tr>
                  <th>Loan No</th>
                  <th>Customer Name</th>
                  <th>Mobile</th>
                  <th>Total Amount</th>
                  <th>EMI Amount</th>
                </tr>
              </thead>
              <tbody>
                {loans.length > 0 ? loans.map((loan) => (
                  <tr key={loan.id}>
                    <td style={{ fontWeight: '500', color: 'var(--primary-color)' }}>{loan.loan_no}</td>
                    <td>{loan.customer_name}</td>
                    <td>{loan.mobile}</td>
                    <td>₹{loan.total_amount}</td>
                    <td>₹{loan.emi_amount}</td>
                  </tr>
                )) : (
                  <tr>
                    <td colSpan="5" style={{ textAlign: 'center' }}>No loans found</td>
                  </tr>
                )}
              </tbody>
            </table>
          )}
        </div>
      </div>

      {showModal && (
        <div style={{ position: 'fixed', top: 0, left: 0, right: 0, bottom: 0, background: 'rgba(0,0,0,0.5)', display: 'flex', justifyContent: 'center', alignItems: 'center', zIndex: 1000 }}>
          <div className="glass-panel" style={{ width: '100%', maxWidth: '500px', background: 'var(--bg-color)' }}>
            <h2 style={{ marginBottom: '24px' }}>Create New Loan</h2>
            <form onSubmit={handleCreateLoan}>
              <div className="form-group">
                <label className="form-label">Loan Number</label>
                <input required type="text" className="form-control" value={formData.loan_no} onChange={e => setFormData({...formData, loan_no: e.target.value})} />
              </div>
              <div className="form-group">
                <label className="form-label">Customer Name</label>
                <input required type="text" className="form-control" value={formData.customer_name} onChange={e => setFormData({...formData, customer_name: e.target.value})} />
              </div>
              <div style={{ display: 'flex', gap: '16px' }}>
                <div className="form-group" style={{ flex: 1 }}>
                  <label className="form-label">Mobile</label>
                  <input required type="text" className="form-control" value={formData.mobile} onChange={e => setFormData({...formData, mobile: e.target.value})} />
                </div>
              </div>
              <div className="form-group">
                <label className="form-label">Address</label>
                <textarea required className="form-control" value={formData.address} onChange={e => setFormData({...formData, address: e.target.value})} />
              </div>
              <div style={{ display: 'flex', gap: '16px' }}>
                <div className="form-group" style={{ flex: 1 }}>
                  <label className="form-label">Total Amount</label>
                  <input required type="number" className="form-control" value={formData.total_amount} onChange={e => setFormData({...formData, total_amount: e.target.value})} />
                </div>
                <div className="form-group" style={{ flex: 1 }}>
                  <label className="form-label">EMI Amount</label>
                  <input required type="number" className="form-control" value={formData.emi_amount} onChange={e => setFormData({...formData, emi_amount: e.target.value})} />
                </div>
              </div>
              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '12px', marginTop: '24px' }}>
                <button type="button" className="btn" style={{ background: 'transparent', color: 'var(--text-primary)', border: '1px solid var(--border-color)' }} onClick={() => setShowModal(false)}>Cancel</button>
                <button type="submit" className="btn btn-primary">Create</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default Loans;
