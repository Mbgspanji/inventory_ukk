<table>
    <thead>
        <tr>
            <th>Item</th>
            <th>Total</th>
            <th>Name</th>
            <th>Ket.</th>
            <th>Date</th>
            <th>Return Date</th>
            <th>Edited By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($details as $detail)
            <tr>
                <td>{{ $detail->item->name ?? 'Unknown' }}</td>
                <td>{{ $detail->total }}</td>
                <td>{{ $detail->lending->borrower_name }}</td>
                <td>{{ $detail->lending->keterangan }}</td>
                <td>{{ \Carbon\Carbon::parse($detail->lending->date)->format('M d, Y') }}</td>
                <td>{{ $detail->lending->returned_date ? \Carbon\Carbon::parse($detail->lending->returned_date)->format('M d, Y') : '-' }}</td>
                <td>{{ $detail->lending->editor->name ?? ($detail->lending->returned_date ? '-' : '') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
