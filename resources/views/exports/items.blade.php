<table>
    <thead>
        <tr>
            <th style="background-color: #f2f2f2; font-weight: bold; text-align: center;">Category</th>
            <th style="background-color: #f2f2f2; font-weight: bold; text-align: center;">Name Item</th>
            <th style="background-color: #f2f2f2; font-weight: bold; text-align: center;">Total</th>
            <th style="background-color: #f2f2f2; font-weight: bold; text-align: center;">Repair Total</th>
            <th style="background-color: #f2f2f2; font-weight: bold; text-align: center;">Last Updated</th>
        </tr>
    </thead>
    <tbody>
        @php
            $months = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
        @endphp
        @foreach($items as $item)
            @php
                $date = \Carbon\Carbon::parse($item->updated_at);
                $formattedDate = $date->format('d') . ' - ' . $months[$date->month] . ' - ' . $date->format('Y');
            @endphp
            <tr>
                <td>{{ $item->category->name ?? '-' }}</td>
                <td>{{ $item->name }}</td>
                <td style="text-align: center;">{{ $item->total }}</td>
                <td style="text-align: center;">{{ $item->repair }}</td>
                <td>{{ $formattedDate }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
