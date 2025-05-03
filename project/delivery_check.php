<?php

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function checkDeliveryAvailability($address, $store_lat, $store_lng, $max_radius = 10) {
   
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    if (empty($address)) {
        return [
            'success' => false,
            'message' => 'Please provide a valid address',
            'is_deliverable' => false
        ];
    }
    
    $encoded_address = urlencode($address);
    
  
    $timestamp = time();
    $url = "https://nominatim.openstreetmap.org/search?format=json&q={$encoded_address}&limit=1&_={$timestamp}";
    
    $options = [
        'http' => [
            'header' => "User-Agent: Shopfinity Delivery Checker/1.0 (contact@example.com)\r\n" .
                        "Accept: application/json\r\n" .
                        "Referer: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'https://example.com') . "\r\n"
        ]
    ];
    
    $context = stream_context_create($options);
    
    
    try {
        $response = @file_get_contents($url, false, $context);
        
     
        if ($response === false) {
            throw new Exception('Unable to connect to geocoding service');
        }
        
        $data = json_decode($response, true);
        
        if (empty($data)) {
            return [
                'success' => false,
                'message' => 'Address not found. Please try a more specific address.',
                'is_deliverable' => false
            ];
        }
        
     
        $lat = (float) $data[0]['lat'];
        $lng = (float) $data[0]['lon'];
        $display_name = isset($data[0]['display_name']) ? $data[0]['display_name'] : $address;
        
        
        $distance = calculateDistance($lat, $lng, $store_lat, $store_lng);
        $is_deliverable = $distance <= $max_radius;
        
        
        $_SESSION['customer_location'] = [
            'lat' => $lat,
            'lng' => $lng,
            'address' => $display_name
        ];
        
        return [
            'success' => true,
            'distance' => $distance,
            'distance_formatted' => number_format($distance, 2) . ' km',
            'is_deliverable' => $is_deliverable,
            'message' => $is_deliverable 
                ? "Good news! Your address is within our delivery area."
                : "Sorry, your address is outside our delivery area of {$max_radius} km.",
            'coordinates' => [
                'lat' => $lat,
                'lng' => $lng
            ]
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'is_deliverable' => false
        ];
    }
}

function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $radius = 6371; 
    
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
         sin($dLon/2) * sin($dLon/2);
         
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $radius * $c;
    
    return $distance;
}


session_start();


$store_location = [
    'name' => 'Shopfinity Main Store',
    'lat' =>41.328536883921984, 
    'lng' => 19.8161055101949,
];


$delivery_radius = 15;


$result = null;
$customer_coordinates = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['address'])) {
    $customer_address = sanitizeInput($_POST['address']);
    $result = checkDeliveryAvailability(
        $customer_address,
        $store_location['lat'],
        $store_location['lng'],
        $delivery_radius
    );
    
    if ($result['success']) {
        $customer_coordinates = $result['coordinates'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delivery Checker - Shopfinity</title>
    <link rel="stylesheet" href="/project/index.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .delivery-form {
            max-width: 600px;
            margin: 30px auto;
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .form-title {
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }
        
        .result-container {
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
        
        .deliverable {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .not-deliverable {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .custom-marker {
            text-align: center;
            border-radius: 50%;
            color: white;
            font-weight: bold;
        }
        
        .store-marker {
            background-color: #4CAF50;
            border: 2px solid white;
        }
        
        .customer-marker {
            background-color: #2196F3;
            border: 2px solid white;
        }
        
        .loading-spinner {
            display: none;
            margin: auto;
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
                flex-direction: column;
            }
            #map {
                height: 300px;
                margin-bottom: 20px;
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
                    <a href="/project/delivery_check.php" class="active">Delivery Check</a>
                    <a href="/project/lokacion.php">Stores</a>
                </nav>
                <a href="/project/index.html" class="back-button">← Back</a>
            </div>
        </div>
    </header>

    <main class="container">
        <h1>Check Delivery Availability</h1>
        <p>Enter your address to check if we deliver to your location.</p>
        
        <div class="row">
            <div class="col-md-6">
                <div class="delivery-form">
                    <h2 class="form-title">Delivery Address</h2>
                    <form method="post" action="" id="delivery-form">
                        <div class="mb-3">
                            <label for="address" class="form-label">Your Address:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="address" name="address" required
                                       placeholder="Enter your full address" 
                                       value="<?php echo isset($_POST['address']) ? sanitizeInput($_POST['address']) : ''; ?>">
                                <button type="submit" class="btn btn-success" id="submit-btn">
                                    Check Availability
                                    <span class="spinner-border spinner-border-sm loading-spinner" id="loading-spinner" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                            <div class="form-text">Example: 123 Main St, City, State, Zip</div>
                        </div>
                    </form>
                    
                    <?php if ($result): ?>
                    <div class="result-container <?php echo $result['is_deliverable'] ? 'deliverable' : 'not-deliverable'; ?>">
                        <h4><?php echo $result['message']; ?></h4>
                        <?php if ($result['success']): ?>
                            <p>Distance from our store: <?php echo $result['distance_formatted']; ?></p>
                            <?php if (!$result['is_deliverable']): ?>
                                <p>Our maximum delivery radius is <?php echo $delivery_radius; ?> km.</p>
                            <?php else: ?>
                                <p>Estimated delivery time: <?php echo ceil($result['distance'] * 5 + 15); ?> minutes</p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-6">
                <div id="map"></div>
                <p><small>Our store location (green) and delivery radius (green circle) are shown on the map. Your location will appear in blue when you search.</small></p>
            </div>
        </div>
    </main>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
       
        const map = L.map('map').setView([<?php echo $store_location['lat']; ?>, <?php echo $store_location['lng']; ?>], 11);
        
       
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        
        const StoreIcon = L.divIcon({
            className: 'custom-marker store-marker',
            iconSize: [30, 30],
            iconAnchor: [15, 15],
            popupAnchor: [0, -15],
            html: '<i class="fas fa-store"></i>'
        });
        
        
        const CustomerIcon = L.divIcon({
            className: 'custom-marker customer-marker',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -10],
            html: '<i class="fas fa-home"></i>'
        });
        
      
        const storeMarker = L.marker(
            [<?php echo $store_location['lat']; ?>, <?php echo $store_location['lng']; ?>], 
            {icon: StoreIcon}
        ).addTo(map);
        storeMarker.bindPopup("<b><?php echo $store_location['name']; ?></b><br>Our store location");
        
        
        L.circle(
            [<?php echo $store_location['lat']; ?>, <?php echo $store_location['lng']; ?>], 
            {
                color: '#4CAF50',
                fillColor: '#4CAF50',
                fillOpacity: 0.2,
                radius: <?php echo $delivery_radius * 1000; ?> 
            }
        ).addTo(map);
        
      
        <?php if ($customer_coordinates): ?>
            const customerMarker = L.marker(
                [<?php echo $customer_coordinates['lat']; ?>, <?php echo $customer_coordinates['lng']; ?>], 
                {icon: CustomerIcon}
            ).addTo(map);
            customerMarker.bindPopup("<b>Your Location</b><br><?php echo isset($_SESSION['customer_location']['address']) ? addslashes($_SESSION['customer_location']['address']) : 'Your address'; ?>");
            
          
            const group = new L.featureGroup([storeMarker, customerMarker]);
            map.fitBounds(group.getBounds().pad(0.5));
        <?php endif; ?>
        
       
         document.getElementById('delivery-form').addEventListener('submit', function() {
            document.getElementById('loading-spinner').style.display = 'inline-block';
            document.getElementById('submit-btn').disabled = true;
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>