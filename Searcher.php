<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait Searcher
{
	private $targets;

	private $fields;
	
	private $modelRelations;
	
	private $modelRelationsFields;

	private $resultQuery;

	public function search($targets, $options = NULL)
	{
		$this->addVariables($targets, $options);

		return $this->searching();
	}

	private function addVariables($targets, $options)
	{
		$this->targets = $this->deconstructTarget($targets);
		$this->fields = $this->optionsItems($options, 'fields');
		$this->modelRelations = $this->optionsItems($options, 'relations');
		$this->modelRelationsFields = $this->optionsItems($options, 'relations_fields');
		$this->resultQuery = $this->query();
	}

	private function deconstructTarget($targets)
	{
		return explode(' ', $targets);
	}

	private function optionsItems($options, $key)
	{
		if ($options && array_key_exists($key, $options))
		{
			$this->validateOption($options, $key);

			return $options[$key];
		}

		if ($key == 'fields')
		{
			return $this->getFillable();
		}

		return NULL;
	}

	private function validateOption($options, $key)
	{
		$rules = 'array';
		
		$validator = Validator::make($options, [
            $key => $rules
        ]);

        if ($validator->fails())
        {
        	abort(400, 'Error getting option: ' . $validator->errors()->first());
        }
	}

	private function searching()
	{
		foreach ($this->targets as $target)
		{
			$this->orWheres($target);
		}

		return $this->resultQuery;
	}

	private function orWheres($target)
	{
		$this->resultQuery->where(function ($query) use ($target) {
			$typeWhere = 'where';

			foreach ($this->fields as $field)
			{
				$query->$typeWhere($field, 'LIKE', '%' . $target . '%');

				$typeWhere = 'orWhere';
			}

			if ($this->modelRelations)
			{
				$this->modelRelationsWhereHas($target, $query);
			}
		});
	}

	public function modelRelationsWhereHas($target, $tailQuery)
	{
		foreach ($this->modelRelations as $relation)
		{
			$tailQuery->orWhere(function ($subQuery) use ($target, $relation) {
				$subQuery->whereHas($relation, function ($query) use ($target) {
					$typeWhere = 'where';

					foreach ($this->modelRelationsFields as $field)
					{
						$query->$typeWhere($field, 'LIKE', '%' . $target . '%');
					}

					$typeWhere = 'orWhere';
				});
			});
		}
	}
}
