<?php
namespace App\Imports;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Supplier;
use App\Helper\CoreAccounts;
use App\Models\PurchaseOrder;
use App\Models\Admin\Branches;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class PurchaseOrderImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            $purchaseOrder = [];
            $purchaseOrderAdded = [];
            
            foreach ($rows as $key => $row) {
                // Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['order_date'])->format('Y-m-d'))->toDateString()
                // Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['delivery_date'])->format('Y-m-d'))->toDateString()
                $supplier = Supplier::where('name', 'like', "%" . $row['supplier_name'] . "%")->first();
                $branch = Branches::where('name', 'like', "%" . $row['branch_name'] . "%")->first();
                $item = Item::where('name', 'like', "%" . $row['item_name'] . "%")->first();

                if (
                    $supplier == Null ||
                    $branch == Null ||
                    $item == Null
                ) {
                    $purchaseOrder += $row;
                } else {
                    $purchaseOrderAdded[] = $row;
                    $order_date = Carbon::parse(Date::excelToDateTimeObject($row['order_date']))->toDateString();
                    $delivery_date = Carbon::parse(Date::excelToDateTimeObject($row['delivery_date']))->toDateString();

                    $purchaseOrder = PurchaseOrder::where('branch_id', $branch->id)
                        ->where('total_amount', $row['total_amount'])
                        ->where('order_date', $order_date)
                        ->where('delivery_date', $delivery_date)
                        ->first();
                    if (!$purchaseOrder) {
                        $purchaseOrder = new PurchaseOrder();
                    }


                    $purchaseOrder->supplier_id = $supplier->id;
                    $purchaseOrder->branch_id = $branch->id;
                    $purchaseOrder->total_amount = $row['total_amount'];
                    $purchaseOrder->order_date = $order_date;
                    $purchaseOrder->delivery_date = $delivery_date;
                    $purchaseOrder->delivery_status = $row['delivery_status'];
                    $purchaseOrder->payment_status = $row['payment_status'];
                    $purchaseOrder->payment_method = $row['payment_method'];
                    $purchaseOrder->type = $row['type'];
                    $purchaseOrder->save();

                    $poi = PurchaseOrderItem::createOrUpdate([
                        'item_id' => $item->id,
                        'purchase_order_id' => $purchaseOrder->id,
                    ],[
                        'quantity' => $row['quantity'],
                        'received_quantity' => $row['received_quantity'],
                        'unit_price' => $row['unit_price'],
                        'total_price' => $row['total_price'],
                        'quote_item_price' => $row['quote_item_price'],
                        'measuring_unit' => $row['measuring_unit'],
                    ]);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
