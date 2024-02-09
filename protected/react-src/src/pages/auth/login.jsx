import { useEffect, useState } from 'react';
import L from 'leaflet';
import { fetchCsfrToken } from '../../services/apiProxyService';
import { doLogin } from '../../services/apiProxyService';
const AuthLogin = () => {
  const {email, setEmail} = useState('');
  const {password, setPassword} = useState('');
  const login = () => {
    const data = {
      email,
      password
    }
    if (email && password) {
      doLogin(data).then((res) => {
        console.log(res);
      })
    }
  }
  useEffect(() => {
    const map = L.map('map', {
      preferCanvas: true,
      center: [8.6753, 9.082],
      zoom: 2.5,
      zoomControl: false,
      maxZoom: 3,
      minZoom: 2,
    });

    for (const baseLayer of [
      {
        type: 'tileLayer',
        url:
          'https://services.arcgisonline.com/arcgis/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}',
        options: {
          maxZoom: 30,
          maxNativeZoom: 17,
        },
      },
    ]) {
      switch (baseLayer.type) {
        case 'tileLayer':
          L.tileLayer(baseLayer.url, baseLayer.options || {}).addTo(map);
          break;
        default:
          break;
      }
    }

    map._handlers.forEach(function (handler) {
      handler.disable();
    });
    fetchCsfrToken().then((res) => {
      console.log(res);
    })

    return () => {
      // Limpiar recursos al desmontar el componente si es necesario
    };
  }, []);

  return (<>
    <div style={{ position: 'relative', display: 'flex', justifyContent: 'center', alignItems: 'center', height: '100vh' }}>
      <div id="map" style={{ height: '100%', width: '100%', zIndex: 1, position: 'absolute' }}></div>
      <div style={{ position: 'absolute', width: '100%', height: '100%', backgroundColor: 'rgba(0,0,0,0.5)', zIndex: 2, display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
        <div style={{ display: 'flex', maxWidth: '900px', minWidth: '900px' }}>
          <div style={{ flex: '1', backgroundColor: '#424348', color: 'white', padding: '30px', minWidth: '450px', maxWidth: '450px', width: '100%' }}>
            <div style={{ display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center', width: '100%' }} className='intro'>
              <img src="../img/HeRAMS_white.svg" alt="logo" style={{ width: '80%' }} />
              <p style={{ marginTop: '20px', textAlign: 'justified' }}>
                The Health Resources and Services Availability Monitoring System (HeRAMS) is a collaborative approach aimed at ensuring that core information on essential health resources and services is systematically shared and readily available to decision makers at country, regional, and global levels
              </p>
              <div style={{ display: 'flex', width: '100%', justifyContent: 'space-around', marginTop: '20px'}}>
                <div style={{ textAlign: 'center' }}>
                  <i className="material-icons">assessment</i>
                  <p>0</p>
                  <p>Projects</p>
                </div>
                <div style={{ textAlign: 'center' }}>
                  <i className="material-icons">add_business</i>
                  <p>0</p>
                  <p>HSDUs</p>
                </div>
                <div style={{ textAlign: 'center' }}>
                  <i className="material-icons">groups</i>
                  <p>0</p>
                  <p>Contributors</p>
                </div>
              </div>
            </div>
          </div>
          <div style={{ flex: '1', flexDirection:'column', textAlign: 'center', padding: '20px', minWidth: '450px', maxWidth: '450px', width: '100%', backgroundColor:'white' }}>
            <div>
              <form method='post' action='/session/create' className='d-flex' style={{gap:'30px', textAlign:'left'}}>
                <h2 className='text-primary h5'>{replaceVariablesAsText('Login')}</h2>
                <div className='w-100'>
                  <div className="form-group">
                    <label htmlFor="email">{replaceVariablesAsText('Email address')} <i className='text-red'> *</i></label>
                    <input 
                      onChange={(e) => setEmail(e.target.value)}
                      value={email}
                      type="email" 
                      className="form-control" 
                      id="email" 
                      name="username" 
                      placeholder="Enter email" />
                  </div>
                  <div className="form-group">
                    <label htmlFor="password">{replaceVariablesAsText('Password')}</label>
                    <input
                      onChange={(e) => setPassword(e.target.value)} 
                      value={password}
                      type="password" 
                      className="form-control"  
                      id="password" 
                      name='current-password' 
                      placeholder="Password" />
                  </div>
                  <div className="form-group form-check">
                    
                    <label className="form-check-label" htmlFor="remember">
                      {replaceVariablesAsText('Remember me')}
                    </label>
                  </div>
                  <button type="button" className="btn btn-default">{replaceVariablesAsText('Login')}</button>
                </div>
              </form>
              <hr className='mt-2' />
            </div>
          </div>
        </div>
      </div>
    </div>
  </>);
};

export default AuthLogin;
