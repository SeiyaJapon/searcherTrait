<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>{{ trans('app.common.name') }}</th>
			<th>{{ trans('app.common.email') }}</th>
			<th>{{ trans('app.common.role') }}</th>
			<th>{{ trans('app.common.enabled') }}</th>
			<th>{{ trans('app.common.locked') }}</th>
			<th width="5%"></th>
		</tr>
		<tbody>
			@forelse ($users as $user)
				<tr>
					<td class="align-middle">
						<a href="{{ route('users.edit', $user->user_id) }}">
							{{ $user->given_name }}{{ ($user->family_name) ? ' ' . $user->family_name : '' }}
						</a>
					</td>
					<td class="align-middle">
						<a href="mailto:{{ $user->email }}" class="text-info">{{ $user->email }}</a>
					</td>
					<td class="align-middle">
						@if (isset($roles[$user->user_id]))
							@foreach ($roles[$user->user_id] as $role)
								<span class="badge badge-secondary" data-toggle="tooltip" data-placement="top" title="{{ $role['service'] }}">
									{{ $role['name'] }}
								</span>

								@if ($loop->count > 1)
									<br>
								@endif
							@endforeach
						@endif
					</td>
					<td class="align-middle">
						<span class="badge badge-{{ ($user->enabled) ? 'success' : 'danger' }}">
							{{ ($user->enabled) ? trans('app.common.enabled') : trans('app.common.not_enabled') }}
						</span>
					</td>
					<td class="align-middle">
						<span class="badge badge-{{ ($user->locked) ? 'dark' : 'secondary' }}">
							{{ ($user->locked) ? trans('app.common.locked') : trans('app.common.not_locked') }}
						</span>
					</td>
					<td class="text-center align-middle">
						<a href="#" class="delete-item text-danger" data-id="{{ $user->user_id }}">
							<i class="fa fa-trash" aria-hidden="true"></i>
						</a>
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="3">{{ trans('app.common.no_registers') }}</td>
				</tr>
			@endforelse
		</tbody>
	</thead>
</table>

{{ $users->links() }}

<script>
    $('li.page-item').find('a.page-link').on('click', function (e) {
        e.preventDefault();

        var result = $(this).attr('href').split('=');

        var id = result[1];

        ajaxSearch($('input[name="search"]').val(), id);

        return false;
    });
</script>
