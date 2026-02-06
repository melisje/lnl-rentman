@extends('layouts.app')

@section('content')
<div class="container">
    <div class="bg-light p-5 rounded-3">
        CONFIGURE ROLES

        <table class="table table-striped">
            <thead>
                <tr>
                    <th role="column">id</th>
                    <th role="column">name</th>
                    @foreach($roles as $role)
                    <th>{{ $role->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>

                    @foreach($user->role_statuses_list as $roleStatus)
                    <td class="role-item">
                        <input type="checkbox" name="roles[]" value="{{ $roleStatus['id'] }}" @checked($roleStatus['assigned']) data-user-id="{{ $user->id }}" data-role-id="{{ $roleStatus['id'] }}" data-route="{{ route('admin.roles.user.toggle', [$roleStatus['role'],$user]) }}" onchange="toggleRoleAssignment(this)">
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>


        {{-- <div>{{ $users }}</div>
        <div>{{ $roles }}</div> --}}
    </div>

</div>
@endsection

@push('scripts')
<script>
    /**
     * Send an asynchrone request to the controller when the checkbox changes.
     * @param {HTMLInputElement} checkboxElement - The changed checkbox-element.
     */
    function toggleRoleAssignment(checkboxElement) {
        // Fetch needed data from data-attributes
        const userId = checkboxElement.dataset.userId;
        const roleId = checkboxElement.dataset.roleId;
        const routeUrl = checkboxElement.dataset.route;
        const isChecked = checkboxElement.checked; // true of false

        // 1. Show a visual status change (eg. loader)
        console.log(`Sending change: Rol ${roleId} for user ${userId}, Status: ${isChecked}`);

        // 2. Make a POST-data object
        const data = {
            role_id: roleId,
            user_id: userId,
            is_assigned: isChecked,
            _method: 'PATCH', // Needed to simulate the PATCH method (in Laravel web routes)
            _token: '{{ csrf_token() }}' // Securitytoken
        };

        // 3. Send a AJAX-request with Axios
        axios.post(routeUrl, data)
            .then(response => {
                // Succes! Show the controller message
                console.log('Succes:', response.data.message);
                console.log('Succes:', response.data.isAssigned);
                // Optional: Update a notification on the page
                // showNotification(response.data.message, 'success');
            })
            .catch(error => {
                // Something went wrong
                console.error('Error during update:', error.response);

                // IMPORTANT: Rollback the checkbox status
                checkboxElement.checked = !isChecked;

                alert('An error occurred while updating the role.');
            });
    }
</script>
@endpush