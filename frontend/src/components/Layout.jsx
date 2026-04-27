import React, { useContext } from 'react';
import { Outlet, Link, useNavigate, useLocation } from 'react-router-dom';
import { AuthContext } from '../AuthContext';
import { LayoutDashboard, Users, PlusCircle, LogOut } from 'lucide-react';

const Layout = () => {
  const { user, logout } = useContext(AuthContext);
  const navigate = useNavigate();
  const location = useLocation();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  const NavItem = ({ to, icon: Icon, children }) => {
    const isActive = location.pathname.startsWith(to);
    return (
      <Link
        to={to}
        style={{
          display: 'flex',
          alignItems: 'center',
          gap: '12px',
          padding: '12px 16px',
          borderRadius: '8px',
          textDecoration: 'none',
          color: isActive ? '#fff' : 'var(--text-secondary)',
          background: isActive ? 'linear-gradient(135deg, rgba(79, 70, 229, 0.2), transparent)' : 'transparent',
          borderLeft: isActive ? '3px solid var(--primary-color)' : '3px solid transparent',
          marginBottom: '8px',
          transition: 'all 0.2s',
          fontWeight: isActive ? '600' : '500'
        }}
      >
        <Icon size={20} color={isActive ? 'var(--primary-color)' : 'currentColor'} />
        {children}
      </Link>
    );
  };

  return (
    <div className="app-container">
      <div className="sidebar glass-panel" style={{ borderRight: '1px solid var(--border-color)', borderRadius: '0 16px 16px 0' }}>
        <div style={{ marginBottom: '40px', padding: '0 16px' }}>
          <h2 style={{ color: 'var(--primary-color)', margin: 0 }}>LCS System</h2>
          <p style={{ fontSize: '12px', color: 'var(--text-secondary)' }}>Welcome, {user?.name}</p>
          <span style={{ 
            fontSize: '11px', 
            padding: '2px 8px', 
            borderRadius: '12px', 
            background: 'rgba(79, 70, 229, 0.2)',
            color: 'var(--primary-color)'
          }}>
            {user?.role.replace('_', ' ').toUpperCase()}
          </span>
        </div>

        <nav style={{ flex: 1 }}>
          <NavItem to="/dashboard" icon={LayoutDashboard}>Dashboard</NavItem>
          <NavItem to="/loans" icon={Users}>Loans</NavItem>
          <NavItem to="/collections/new" icon={PlusCircle}>Add Collection</NavItem>
        </nav>

        <button 
          onClick={handleLogout}
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '12px',
            padding: '12px 16px',
            background: 'none',
            border: 'none',
            color: 'var(--danger)',
            cursor: 'pointer',
            fontWeight: '500',
            fontFamily: 'inherit',
            width: '100%',
            textAlign: 'left'
          }}
        >
          <LogOut size={20} />
          Logout
        </button>
      </div>
      <div className="main-content">
        <Outlet />
      </div>
    </div>
  );
};

export default Layout;
