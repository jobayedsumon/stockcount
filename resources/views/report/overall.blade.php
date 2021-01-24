<table>
    <thead>
    <tr>
        <th>S/L</th>
        <th>Distributor Name</th>
        <th>Distributor Code</th>
        <th>Opening Date</th>
        <th>Product Name</th>
        <th>Product Code</th>
        <th>PKD</th>
        <th>Opening Stock</th>
        <th>Already Received</th>
        <th>In Transit</th>
        <th>Delivery Done</th>
        <th>In Delivery Van</th>
        <th>Physical Stock</th>
        <th>Total Stock</th>
        <th>Created At</th>
        <th>Updated At</th>
    </tr>
    </thead>
    <tbody>
    @forelse($product_stock as $data)
        <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $data->stock->distributor->name }}</td>
            <td>{{ $data->stock->distributor->code }}</td>
            <td>{{ $data->stock->stock_opening_date }}</td>
            <td>{{ $data->product->name }}</td>
            <td>{{ $data->product->code }}</td>
            <td>{{ $data->pkg_date }}</td>
            <td>{{ $data->opening_stock }}</td>
            <td>{{ $data->already_received }}</td>
            <td>{{ $data->stock_in_transit }}</td>
            <td>{{ $data->delivery_done }}</td>
            <td>{{ $data->in_delivery_van }}</td>
            <td>{{ $data->physical_stock }}</td>

        @php

            $total = 0;

            $total += $data->opening_stock;
            $total += $data->already_received;
            $total += $data->stock_in_transit;
            $total -= $data->delivery_done;
            $total -= $data->in_delivery_van;
            $total += $data->physical_stock;

        @endphp
        <td>{{ $total }}</td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->updated_at }}</td>

        </tr>

        @empty
        @endforelse

    </tbody>
</table>
