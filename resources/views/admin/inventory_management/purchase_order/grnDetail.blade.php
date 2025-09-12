@extends('admin.layouts.main')

@section('title')
GRN
@stop

@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        form, form * {
            visibility: visible;
        }
        form {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        button {
            display:none !important;
        }
    }
</style>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Goods Received Note</h4>
        </div>
        <div class="card-body">

            <form id="frm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" value="{{$purchaseOrder->supplier->name}}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Branch</label>
                        <input type="text" class="form-control" value="{{$purchaseOrder->branch->name}}" readonly>
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Received Date</label>
                        <input type="text" class="form-control" value="{{$purchaseOrder->delivery_date}}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Order Date</label>
                        <input type="text" class="form-control" value="{{$purchaseOrder->order_date}}" readonly>
                    </div>

                </div>

                <h5 class="mt-4">Purchase Details</h5>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrder->purchaseOrderItems as $i)
                        <tr>
                            <td><input type="text" class="form-control" value="{{$i->item->name ?? ''}}" readonly></td>
                            <td><input type="number" class="form-control" value="{{$i->quantity}}" readonly></td>
                            <td><input type="number" class="form-control" value="{{$i->unit_price}}" readonly></td>
                            <td><input type="number" class="form-control" value="{{$i->total_price}}" readonly></td>
                        </tr>
                        @endforeach()
                    </tbody>
                </table>

                <div class="d-flex justify-content-between mt-4" id="printBtn">
                    <button type="button" class="btn btn-secondary"  onclick="printForm()">Print</button>
                </div>

            </form>

        </div>
    </div>
</div>
<script>
   function printForm() {
    var form = document.getElementById("frm");
    var printWindow = window.open('', '', 'width=800,height=600');

    var formClone = form.cloneNode(true);

    var printButton = formClone.querySelector("#printBtn");
    if (printButton) {
        printButton.remove();
    }

    printWindow.document.write('<html><head><title>Print</title>');

    var styles = document.head.innerHTML;
    printWindow.document.write(styles);

    printWindow.document.write('</head><body>');
    printWindow.document.write('<div class="container mt-4">');
    printWindow.document.write(formClone.outerHTML);
    printWindow.document.write('</div>');
    printWindow.document.write('</body></html>');

    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}

</script>

@endsection
