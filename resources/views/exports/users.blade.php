@php
    $desiredOrder = ['name', 'role', 'phone', 'email', 'address', 'status'];
    usort($columns, function($a, $b) use ($desiredOrder) {
        $posA = array_search($a, $desiredOrder);
        $posB = array_search($b, $desiredOrder);
        return $posA - $posB;
    });
@endphp
<table>
    <thead>
        <tr>
            @foreach($columns as $column)
                <th>{{ str_replace('_', ' ', $column) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                @if(in_array('name', $columns))
                    <td>{{ $user->name }}</td>
                @endif
                @if(in_array('role', $columns))
                    <td>{{ $user->role ? $user->role->name : null }}</td>
                @endif
                @if(in_array('phone', $columns))
                    <td>{{ $user->phone }}</td>
                @endif
                @if(in_array('email', $columns))
                    <td>{{ $user->email }}</td>
                @endif
                @if(in_array('address', $columns))
                    <td>{{ $user->address }}</td>
                @endif
                @if(in_array('status', $columns))
                    <td>{{ $user->status }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
