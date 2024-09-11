<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'admin.orders.action')
            ->editColumn('user', function ($row) {
                return $row->user
                    ? sprintf(
                        '<a href="%s" target="_blank">%s</a>',
                        route('admin.users.show', $row->user->id),
                        $row->user->name
                    )
                    : '';
            })
            ->editColumn('items', function ($row) {
                return view('admin.orders.items', ['items' => $row->items]);
            })
            ->editColumn('status', function ($row) {
                $status = Order::STATUSES[$row->status];

                return sprintf(
                    '<span class="badge badge-%s rounded-0">%s</span>',
                    $status['color'],
                    $status['label']
                );
            })
            ->editColumn('amount', fn($row) => 'Rp ' . number_format((float) $row->invoice->amount, 0, ',', '.'))
            ->editColumn('shipping', function ($row) {
                return $row->shipping ? strtoupper($row->shipping->courier) . '-' . $row->shipping->tracking_number : '';
            })
            ->setRowId('id')
            ->rawColumns(['action', 'user', 'items', 'status']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['user', 'items', 'items.product', 'items.product.media', 'invoice', 'shipping'])
            ->select('orders.*')
            ->when(request()->filled('status'), function ($q) {
                return $q->where('status', request('status'));
            });

    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('dataTable-orders')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->selectStyleMultiShift()
            ->selectSelector('td:first-child')
            ->buttons([
                Button::make('selectAll'),
                Button::make('selectNone'),
                Button::make('excel'),
                Button::make('reset'),
                Button::make('reload'),
                Button::make('colvis'),
                Button::make('bulkDelete'),
                Button::make('filter'),
            ])
            ->ajax([
                'data' => '
                    function(d) {
                        $.each($("#form-filter").serializeArray(), function(key, val) {
                            d[val.name] = val.value;
                        })
                    }'
            ])
        ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::checkbox('&nbsp;')->exportable(false)->printable(false)->width(35),
            Column::make('id')->title('ID'),
            Column::make('invoice.number', 'invoice.number')->visible(false),
            Column::make('user', 'user.name')->title('Buyer'),
            Column::make('items', 'items.name')->title('Product(s)')->orderable(false),
            Column::make('amount', 'invoice.amount'),
            Column::make('shipping', 'shipping.tracking_number'),
            Column::make('status'),
            Column::make('confirmed_at')->visible(false),
            Column::make('completed_at')->visible(false),
            Column::make('cancelled_at')->visible(false),
            Column::make('created_at')->visible(false),
            Column::make('updated_at')->visible(false),
            Column::computed('action', '&nbsp;')->exportable(false)->printable(false)->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Orders_' . date('dmY');
    }
}