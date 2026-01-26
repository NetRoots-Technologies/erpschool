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
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Setting</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>IP Address</strong></td>
                                                <td><code>10.105.187.50</code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Subnet Mask</strong></td>
                                                <td><code>255.255.255.0</code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Gateway</strong></td>
                                                <td><code>10.105.187.250</code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Port</strong></td>
                                                <td><code>4370</code></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning mt-3">
                            <strong>Note:</strong> This configuration has been done in the Cornerstone biometric machine. 
                            Please test and confirm if the connection is established.
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
                                        <label for="device_ip"><strong>Device IP Address</strong></label>
                                        <input type="text" class="form-control" id="device_ip" name="ip" 
                                               value="10.105.187.50" placeholder="e.g., 10.105.187.50" required>
                                        <small class="form-text text-muted">Enter the IP address configured on the device</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="device_port"><strong>Port</strong></label>
                                        <input type="number" class="form-control" id="device_port" name="port" 
                                               value="4370" placeholder="e.g., 4370" required>
                                        <small class="form-text text-muted">Default ZKTeco port is 4370</small>
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
            const port = document.getElementById('device_port').value;
            const testBtn = document.getElementById('testBtn');
            const resultContainer = document.getElementById('resultContainer');
            const resultAlert = document.getElementById('resultAlert');
            const resultTitle = document.getElementById('resultTitle');
            const resultContent = document.getElementById('resultContent');
            const customUrl = document.getElementById('customUrl');
            
            // Show loading
            testBtn.disabled = true;
            testBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Testing Connection...';
            resultContainer.style.display = 'none';
            
            // Build URL (internal API endpoint)
            const url = '{{ url("/zkt-test-connection-api") }}?ip=' + encodeURIComponent(ip) + '&port=' + encodeURIComponent(port);
            
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
