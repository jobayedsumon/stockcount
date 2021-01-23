<h4>Stock data for the distributor: {{ $stock->distributor->name }} ( {!! $stock->declared ? 'Declared' : 'Not Declared' !!} )</h4>
<h4>Stock opening date: {{ $stock->stock_opening_date }}</h4>
<h4>Total products: {{ $stock->products()->count() }}</h4>
<p>Created at: {{ $stock->created_at }}</p>
<p>Last updated at: {{ $stock->updated_at }}</p>
<p>Declare time: {{ $stock->declare_time }}</p>

<br>

<table>
    <thead>
    <tr>
        <th>S/L</th>
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
    @forelse($products as $product)
        <tr>
            <td>{{ $loop->index+1 }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->code }}</td>
            <td>{{ $product->pivot->pkg_date }}</td>
            <td>{{ $product->pivot->opening_stock }}</td>
            <td>{{ $product->pivot->already_received }}</td>
            <td>{{ $product->pivot->stock_in_transit }}</td>
            <td>{{ $product->pivot->delivery_done }}</td>
            <td>{{ $product->pivot->in_delivery_van }}</td>
            <td>{{ $product->pivot->physical_stock }}</td>
            @php

                $total = 0;

                $total += $product->pivot->opening_stock;
                $total += $product->pivot->already_received;
                $total += $product->pivot->stock_in_transit;
                $total -= $product->pivot->delivery_done;
                $total -= $product->pivot->in_delivery_van;
                $total += $product->pivot->physical_stock;

            @endphp
            <td>{{ $total }}</td>
            <td>{{ $product->pivot->created_at }}</td>
            <td>{{ $product->pivot->updated_at }}</td>
        </tr>
    @empty
    @endforelse
    </tbody>
</table>
