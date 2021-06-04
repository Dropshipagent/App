<table class="table table-hover">
    <tr>
        <th>Store</th>
        <th>Created</th>
    </tr>
    @foreach($mapped_stores as $mapped_store)
    <tr>
        <td>{{ $mapped_store->store_domain }}</td>
        <td>{{ $mapped_store->created_at }}</td>
    </tr>
    @endforeach
</table>