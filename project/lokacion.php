<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Store Locator - Shopfinity</title>
    <link rel="stylesheet" href="/project/index.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        #map {
            height: 500px;
            width: 100%;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .store-list {
            margin-top: 20px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .store-item {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .store-item:hover {
            background-color: #f5f5f5;
            border-color:rgb(57, 125, 156);
        }
        
        .store-item.active {
            background-color: #e8f5e9;
            border-color:rgb(36, 96, 131);
        }
        
        .store-name {
            font-weight: bold;
            font-size: 18px;
            color:rgb(15, 58, 114);
        }
        
        .store-address {
            color: #666;
            margin: 5px 0;
        }
        
        .store-contact {
            display: flex;
            margin-top: 5px;
        }
        
        .store-contact a {
            margin-right: 15px;
            color: #4CAF50;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .store-contact a:hover {
            color: #2E7D32;
            text-decoration: underline;
        }
        
        .geocoder-container {
            margin-bottom: 20px;
        }
        
        .geocoder-input {
            width: 70%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
            font-size: 16px;
        }
        
        .geocoder-button {
            padding: 12px 20px;
            background-color:rgb(33, 94, 143);
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .geocoder-button:hover {
            background-color:rgb(18, 47, 100);
        }
        
        .leaflet-popup-content {
            min-width: 220px;
        }
        
        .directions-link {
            display: inline-block;
            margin-top: 10px;
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        
        .directions-link:hover {
            text-decoration: underline;
        }
        
        #loading-indicator {
            display: none;
            text-align: center;
            margin: 10px 0;
        }
        
        #error-message {
            display: none;
            color: #d32f2f;
            background-color: #ffebee;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
   
        .custom-marker {
            background-color: #4CAF50;
            border-radius: 50%;
            border: 2px solid white;
            text-align: center;
            line-height: 30px;
            color: white;
            font-weight: bold;
        }
        
        .user-marker {
            background-color: #2196F3;
            border-radius: 50%;
            border: 2px solid white;
            text-align: center;
            color: white;
        }

        .back-button {
  background-color: rgba(255, 255, 255, 0.2);
  color: #ffffff;
  padding: 5px 15px;
  border-radius: 5px;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 5px;
  transition: background-color 0.3s;
}

.back-button:hover {
  background-color: rgba(255, 255, 255, 0.3);
}

        
      
        @media (max-width: 768px) {
            .row {
                flex-direction: column-reverse;
            }
            #map {
                height: 300px;
                margin-bottom: 20px;
            }
            .store-list {
                max-height: 300px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Shopfinity</div>
                <nav class="nav-menu">
                    <a href="/project/index.html">Home</a>
                    <a href="/project/delivery_check.php">Delivery Check</a>
                    <a href="/project/lokacion.php" class="active">Stores</a>
                </nav>
                <a href="/project/index.html" class="back-button">← Back</a>
            </div>
        </div>
    </header>

    <main class="container">
        <h1>Find Our Stores</h1>
        <p>Locate the nearest Shopfinity store in your area. Click on a store for more details.</p>
        
        <div class="geocoder-container">
            <div class="input-group">
                <input type="text" id="address-input" class="geocoder-input" placeholder="Enter your address or zip code">
                <button id="geocode-button" class="geocoder-button">Find Nearby Stores</button>
            </div>
            <div id="error-message"></div>
            <div id="loading-indicator">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="ms-2">Finding nearby stores...</span>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div id="map"></div>
            </div>
            <div class="col-md-4">
                <div class="store-list" id="store-list">
                  
                </div>
            </div>
        </div>
    </main>
    
   
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
  
        const stores = [
            {
                id: 1,
                name: "Shopfinity1",
                address: "Rruga Elbasanit, Tirane",
                lat: 41.319593,
                lng: 19.827023,
                phone: "+355 68 000 0000",
                hours: "Mon-Sat: 9am - 9pm, Sun: 10am - 6pm",
                email: "downtown@shopfinity.com"
            },
            {
                id: 2,
                name: "Shopfinity2",
                address: "Rruga Rrapo Hekali , Tirane",
                lat: 41.311962,
                lng: 19.800243,
                phone: "+355 68 000 0000",
                hours: "Mon-Sun: 10am - 10pm",
                email: "westside@shopfinity.com"
            },
           
            {
                id: 3,
                name: "Shopfinity3",
                address: "Rruga Ramdan Citaku, Tirane",
                lat: 41.361314,
                lng: 19.772360,
                phone: "+355 68 000 0000",
                hours: "Mon-Sun: 10am - 10pm",
                email: "westside@shopfinity.com"
            },

            {
                id: 4,
                name: "Shopfinity4",
                address: "Rruga Trajan, Durres",
                lat: 41.323981,
                lng: 19.451082,
                phone: "+355 68 000 0000",
                hours: "Mon-Sun: 10am - 10pm",
                email: "westside@shopfinity.com"
            },

            {
                id: 5,
                name: "Shopfinity5",
                address: "Rruga Sadik Zotaj , Vlore",
                lat: 40.467977,
                lng: 19.490754,
                phone: "+355 68 000 0000",
                hours: "Mon-Sun: 10am - 10pm",
                email: "westside@shopfinity.com"
            },

            {
                id: 6,
                name: "Shopfinity6",
                address: "Rruga Sadik Zotaj , Vlore",
                lat: 40.456975,
                lng: 19.487382,
                phone: "+355 68 000 0000",
                hours: "Mon-Sun: 10am - 10pm",
                email: "westside@shopfinity.com"
            },
        ];
        
        
        const map = L.map('map').setView([stores[0].lat, stores[0].lng], 12);
        
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        
        const StoreIcon = L.divIcon({
            className: 'custom-marker',
            iconSize: [30, 30],
            iconAnchor: [15, 15],
            popupAnchor: [0, -15]
        });
        
        const UserIcon = L.divIcon({
            className: 'user-marker',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -10]
        });
        
       
        const markers = {};
        stores.forEach(store => {
            
            const marker = L.marker([store.lat, store.lng], {
                icon: StoreIcon
            })
            .addTo(map)
            .bindPopup(`
                <div class="store-popup">
                    <h3>${store.name}</h3>
                    <p>${store.address}</p>
                    <p><strong>Hours:</strong> ${store.hours}</p>
                    <p><strong>Phone:</strong> <a href="tel:${store.phone}">${store.phone}</a></p>
                    <a href="https://www.openstreetmap.org/directions?from=&to=${store.lat},${store.lng}" 
                       target="_blank" class="directions-link">Get Directions</a>
                </div>
            `);
            
            markers[store.id] = marker;
        });
        
     
        const storeListElement = document.getElementById('store-list');
        
        function populateStoreList() {
            storeListElement.innerHTML = '';
            stores.forEach(store => {
                const storeItem = document.createElement('div');
                storeItem.className = 'store-item';
                storeItem.dataset.storeId = store.id;
                storeItem.innerHTML = `
                    <div class="store-name">${store.name}</div>
                    <div class="store-address">${store.address}</div>
                    <div class="store-hours"><strong>Hours:</strong> ${store.hours}</div>
                    <div class="store-contact">
                        <a href="tel:${store.phone}">Call</a>
                        <a href="mailto:${store.email}">Email</a>
                    </div>
                `;
                
              
                storeItem.addEventListener('click', () => {
                    
                    document.querySelectorAll('.store-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    
                
                    storeItem.classList.add('active');
                    
               
                    map.setView([store.lat, store.lng], 15);
                    markers[store.id].openPopup();
                });
                
                storeListElement.appendChild(storeItem);
            });
        }
        
       
        populateStoreList();
        
       
        let userMarker = null;
        
       
        const geocodeButton = document.getElementById('geocode-button');
        const addressInput = document.getElementById('address-input');
        const loadingIndicator = document.getElementById('loading-indicator');
        const errorMessage = document.getElementById('error-message');
        
      
        addressInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                geocodeButton.click();
            }
        });
        
        geocodeButton.addEventListener('click', function() {
            const address = addressInput.value.trim();
            
            if (!address) {
                showError('Please enter an address to search');
                return;
            }
            
            
            loadingIndicator.style.display = 'block';
            errorMessage.style.display = 'none';
            
        
            geocodeButton.disabled = true;
            
            
            const timestamp = new Date().getTime();
            
            
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&_=${timestamp}`, {
                headers: {
                    'Accept': 'application/json',
                    'User-Agent': 'Shopfinity Store Locator (contact@example.com)' 
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Geocoding service error: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data && data.length > 0) {
                    const location = data[0];
                    const lat = parseFloat(location.lat);
                    const lng = parseFloat(location.lon);
                    
                
                    if (userMarker) {
                        map.removeLayer(userMarker);
                    }
                    
                    
                    userMarker = L.marker([lat, lng], {
                        icon: UserIcon
                    })
                    .addTo(map)
                    .bindPopup(`<strong>Your location</strong><br>${location.display_name}`)
                    .openPopup();
                    
                 
                    map.setView([lat, lng], 12);
                    
               
                    let closestStore = null;
                    let shortestDistance = Infinity;
                    
                    stores.forEach(store => {
                        const distance = calculateDistance(lat, lng, store.lat, store.lng);
                        if (distance < shortestDistance) {
                            shortestDistance = distance;
                            closestStore = store;
                        }
                    });
                    
                    if (closestStore) {
                        setTimeout(() => {
                            
                            markers[closestStore.id].openPopup();
                            
                         
                            document.querySelectorAll('.store-item').forEach(item => {
                                item.classList.remove('active');
                                if (parseInt(item.dataset.storeId) === closestStore.id) {
                                    item.classList.add('active');
                                    item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            });
                            
                           
                            const bounds = L.latLngBounds(
                                [lat, lng],
                                [closestStore.lat, closestStore.lng]
                            );
                            map.fitBounds(bounds.pad(0.3));
                        }, 500);
                    }
                } else {
                    showError('Address not found. Please try a different address.');
                }
            })
            .catch(error => {
                console.error('Error geocoding address:', error);
                showError('Error finding address. Please try again or be more specific.');
            })
            .finally(() => {
             
                loadingIndicator.style.display = 'none';
                geocodeButton.disabled = false;
            });
        });
        
     
        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            loadingIndicator.style.display = 'none';
            geocodeButton.disabled = false;
        }
        
       
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; 
            const dLat = deg2rad(lat2 - lat1);
            const dLon = deg2rad(lat2 - lon1);
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
                Math.sin(dLon/2) * Math.sin(dLon/2); 
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
            const distance = R * c; 
            return distance;
        }
        
        function deg2rad(deg) {
            return deg * (Math.PI/180);
        }
        
        
        const allStoreCoordinates = stores.map(store => [store.lat, store.lng]);
        if (allStoreCoordinates.length > 0) {
            map.fitBounds(L.latLngBounds(allStoreCoordinates).pad(0.3));
        }
    </script>
</body>
</html>