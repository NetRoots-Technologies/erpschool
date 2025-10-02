{{-- @extends('admin.layouts.main') --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th class="fs-6">Sr.</th>
                    @foreach ($fields as $label)
                        <th class="fs-6">{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        @foreach ($fields as $field => $label)
                            <td>
                                {{ data_get($row, $field) ?? '-' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function handlePrint() {
            // Add a small delay before printing to ensure everything is ready
            setTimeout(() => {
                window.print();
            }, 100);

            // Different approaches for different browsers
            if (window.matchMedia) {
                const mediaQueryList = window.matchMedia('print');
                mediaQueryList.addListener((mql) => {
                    if (!mql.matches) {
                        // Print dialog closed
                        setTimeout(() => {
                            window.history.back();
                        }, 300); // Small delay to ensure print is really done
                    }
                });
            }

            // Fallback for browsers that don't support matchMedia listener
            window.onafterprint = function () {
                setTimeout(() => {
                    window.history.back();
                }, 300);
            };
        }

        // Alternative: Auto-print when page loads and then go back
        document.addEventListener('DOMContentLoaded', function () {
            // Uncomment if you want auto-print
            handlePrint();
        });
    </script>
</body>

</html>