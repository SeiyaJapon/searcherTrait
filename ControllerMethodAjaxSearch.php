<?php

// [...]

	public function ajaxSearch(Request $request)
	{
		if ($request->ajax())
		{
			$options = [
				'fields' => ['given_name', 'email'],
				'relations' => ['roles'],
				'relations_fields' => ['name']
			];
			
			$user = new User;
			
			$users = $user->search($request->element, $options)
						  ->paginate(10, ['*'], 'page', $request->page);

			return view('users.partials.table_items', ['users' => $users, 'ajax' => true]);
		}

        abort(405, trans('app.common.no_ajax'));
	}
