@extends('admin.layouts.main')

@section('title')
    ZKTeco Device Connection Test
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mt-4 mb-4">
            <div class="col-12">
                <!-- Device Setup Instructions -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title mb-0"><i class="fa fa-info-circle"></i> ZKTeco Device Setup Instructions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><strong>1. Check ZKTeco Device Physical Setup</strong></h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <i class="fa fa-check-circle text-success"></i> Ensure the device is powered ON
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fa fa-check-circle text-success"></i> Check network cable connection (if using Ethernet)
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fa fa-check-circle text-success"></i> Verify the device display shows network settings
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5><strong>2. Configure ZKTeco Device Network Settings</strong></h5>
                                <p>Go to the device menu:</p>
                                <p><strong>Menu → Communication/Network → TCP/IP Settings</strong></p>
                                <p class="text-muted">Configure the device network settings as per your requirements and enter the IP Address and Port below to test the connection.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Connection Test Form -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Test Device Connection</h3>
                    </div>
                    <div class="card-body">
                        <form id="testConnectionForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="device_ip"><strong>IP Address</strong></label>
                                        <input type="text" class="form-control" id="device_ip" name="ip" 
                                               placeholder="Enter IP address" required>
                                        <small class="form-text text-muted">Enter the IP address configured on the device</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subnet_mask"><strong>Subnet Mask</strong></label>
                                        <input type="text" class="form-control" id="subnet_mask" name="subnet_mask" 
                                               placeholder="Enter subnet mask" required>
                                        <small class="form-text text-muted">Enter the subnet mask (e.g., 255.255.255.0)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gateway"><strong>Gateway</strong></label>
                                        <input type="text" class="form-control" id="gateway" name="gateway" 
                                               placeholder="Enter gateway address" required>
                                        <small class="form-text text-muted">Enter the gateway address</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="device_port"><strong>Port</strong></label>
                                        <input type="number" class="form-control" id="device_port" name="port" 
                                               placeholder="Enter port number" required>
                                        <small class="form-text text-muted">Enter the port number (default: 4370)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg" id="testBtn">
                                    <i class="fa fa-plug"></i> Test Connection
                                </button>
                                <button type="button" class="btn btn-secondary btn-lg" onclick="location.reload()">
                                    <i class="fa fa-refresh"></i> Reset
                                </button>
                            </div>
                        </form>

                        <div id="resultContainer" style="display: none;" class="mt-4">
                            <div class="alert" id="resultAlert" role="alert">
                                <h5 id="resultTitle"></h5>
                                <pre id="resultContent" style="white-space: pre-wrap; max-height: 500px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px;"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('testConnectionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const ip = document.getElementById('device_ip').value;
            const subnetMask = document.getElementById('subnet_mask').value;
            const gateway = document.getElementById('gateway').value;
            const port = document.getElementById('device_port').value;
            const testBtn = document.getElementById('testBtn');
            const resultContainer = document.getElementById('resultContainer');
            const resultAlert = document.getElementById('resultAlert');
            const resultTitle = document.getElementById('resultTitle');
            const resultContent = document.getElementById('resultContent');
            
            // Show loading
            testBtn.disabled = true;
            testBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Testing Connection...';
            resultContainer.style.display = 'none';
            
            // Build URL (internal API endpoint) - send all fields to controller
            const url = '{{ url("/zkt-test-connection-api") }}?ip=' + encodeURIComponent(ip) + 
                       '&port=' + encodeURIComponent(port) + 
                       '&subnet_mask=' + encodeURIComponent(subnetMask) + 
                       '&gateway=' + encodeURIComponent(gateway);
            
            // Make request
            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                resultContainer.style.display = 'block';
                resultContent.textContent = JSON.stringify(data, null, 2);
                
                if (data.status === 'Connected') {
                    resultAlert.className = 'alert alert-success';
                    resultTitle.textContent = '✅ Connection Successful!';
                } else if (data.status === 'Failed') {
                    resultAlert.className = 'alert alert-danger';
                    resultTitle.textContent = '❌ Connection Failed';
                } else {
                    resultAlert.className = 'alert alert-warning';
                    resultTitle.textContent = '⚠️ ' + data.status;
                }
            })
            .catch(error => {
                resultContainer.style.display = 'block';
                resultAlert.className = 'alert alert-danger';
                resultTitle.textContent = '❌ Error';
                resultContent.textContent = 'Error: ' + error.message;
            })
            .finally(() => {
                testBtn.disabled = false;
                testBtn.innerHTML = '<i class="fa fa-plug"></i> Test Connection';
            });
        });
    </script>
@stop
