<table>
    <thead>
        <tr>
            <th style="font-weight: bold; text-align: center;">Name</th>
            <th style="font-weight: bold; text-align: center;">Email</th>
            <th style="font-weight: bold; text-align: center;">Password</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            @php
                $prefix = strtolower(substr($user->name, 0, 4));
                if (strlen($prefix) < 4) {
                    $prefix = strtolower(substr($user->email, 0, 4));
                }
                $defaultPass = $prefix . $user->id;
            @endphp
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    {{ $user->is_password_updated ? 'This account already edited the password' : $defaultPass }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
