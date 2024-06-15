<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>INVOICE NO / CODE</th>
            <th>PRODUCT</th>
            <th>SUPPLIER</th>
            <th>QUANTITY</th>
            <th>ITEM PRICE</th>
            <th>TOTAL PRICE</th>
            <th>STATUS</th>
            <th>TRANSACTION DATE</th>
            <th>USER</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key => $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->invoice_no }}</td>
            <td>{{ $row->product?->name }}</td>
            <td>{{ $row->product?->supplier?->name }}</td>
            <td>{{ $row->quantity }}</td>
            <td>{{ $row->item_price }}</td>
            <td>{{ $row->total_price }}</td>
            <td>{{ $row->status }}</td>
            <td>{{ $row->paid_at }}</td>
            <td>{{ $row->user?->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>