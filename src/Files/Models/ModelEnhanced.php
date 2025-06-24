<?php

namespace App\Models;

use App\Exceptions\ErrorMessageException;
use App\Extras\StatusCodes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * App\Models\ModelEnhanced
 *
 * @method static \Illuminate\Database\Eloquent\Builder page($loadedCount, $perPage = 20)
 * @method static \Illuminate\Database\Eloquent\Builder page2($page, $perPage = 20)
 * @method static \Illuminate\Database\Eloquent\Builder wheree($column, $value, $default = null)
 * @method static \Illuminate\Database\Eloquent\Builder firstOrError($message=null,$cols=["*"] )
 * @method static \Illuminate\Database\Eloquent\Builder findOrError($id,$message=null,$cols=["*"] )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModelEnhanced query()
 * @mixin \Eloquent
 */
class ModelEnhanced extends Model {

    protected $casts = [
        ];

    //
    public static function checkParameter($parameter, $default = null) {

        if ($parameter === [] || $parameter === null || $parameter === '' || $parameter === $default) {
            return false;
        }
        return true;
    }

    // pagination for APIs
    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public function scopePage($query, $loadedCount, $perPage = 20) {
        if (!$perPage) {
            $perPage = 20;
        }
        if (self::checkParameter($loadedCount)) {
            return $query->offset($loadedCount)->limit($perPage);
        }
        return $query;
    }


    // custom pagination for vue components
    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public function scopePage2($query, $page, $perPage = 20) {
        if (!$perPage) {
            $perPage = 20;
        }
        if (self::checkParameter($page)) {
            $offset = ((int)$page - 1) * $perPage;
            return $query->offset($offset)->limit($perPage);
        }
        return $query;
    }

    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public function scopeWheree($query, $column, $value, $default = null) {
        if ($value === [] || $value === null || $value === '' || $value === $default) {
            return $query->where($column, $value);
        }
        return $query;
    }


    //------------------------------------------------------------------------------------------------------------------------------------
    // this function is usually used in accessor & mutators of any image stored in database  to the return full path to the image
    public static function correctImage($image, $path,$noDefalut=false) {
    	if($noDefalut && $image==null){
    		return $image;
		}
        if ($image == '')
            $image = 'default.png';

        if (substr($image, 0, 4) == 'http') {
            return $image;
        }

        return PREFIX_HTML . $path . $image;
    }


	// ------------------------------------------------------------------------------------------------------------------------------
	public  function scopeFirstOrError($query,$message=null, $cols=["*"]){
		$result =  $query -> first($cols);
		if($result == null)
			throw new ErrorMessageException($message?$message:'آیتم یافت نشد',StatusCodes::HTTP_NOT_FOUND);

		return $result;
	}

	// ------------------------------------------------------------------------------------------------------------------------------
	public  function scopeFindOrError($query,$id,$message=null, $cols=["*"]){
		$id=clear($id);
		$result =  $query ->where('id',$id)-> first($cols);
		if($result == null)
			throw new ErrorMessageException($message?$message:'آیتم یافت نشد',StatusCodes::HTTP_NOT_FOUND);

		return $result;
	}

	// ------------------------------------------------------------------------------------------------------------------------------
	public function scopeSort($query, $params) {
		$column = $params['sort'] ?? 'id';
		$direction = strtolower($params['sortType'] ?? 'desc');

		if (!in_array($direction, ['asc', 'desc'])) {
			$direction = 'asc';
		}

		return $query->orderBy($column, $direction);
	}

	// ------------------------------------------------------------------------------------------------------------------------------
	public function scopeFilters($query, $filters) {
		foreach ($filters as $filter) {
			$name = $filter['name'];
			$val = $filter['val']??null;
			$operation1 = $filter['operation1'] ?? null;
			$value1 = $filter['value1'] ?? null;
			$operation2 = $filter['operation2'] ?? null;
			$value2 = $filter['value2'] ?? null;
			$logic = strtolower($filter['logic'] ?? 'and');
			if($val){
				$val = str_replace('_text', '', $val);
				$val = str_replace('_fa', '', $val);
			}

			$query->where(function ($q) use ($name, $val, $operation1, $value1, $operation2, $value2, $logic) {
				$callbacks = [];

				if (!is_null($value1) || in_array($operation1, ['is_null', 'is_not_null', 'is_empty', 'is_not_empty'])) {
					$callbacks[] = $this->buildCondition($name, $val, $operation1, $value1);
				}

				if (!is_null($value2) || in_array($operation2, ['is_null', 'is_not_null', 'is_empty', 'is_not_empty'])) {
					$callbacks[] = $this->buildCondition($name, $val, $operation2, $value2);
				}

				if (count($callbacks) === 1) {
					$callbacks[0]($q);
				} elseif (count($callbacks) === 2) {
					if ($logic === 'or') {
						$q->where(function ($sub) use ($callbacks) {
							$callbacks[0]($sub);
							$sub->orWhere(function ($orSub) use ($callbacks) {
								$callbacks[1]($orSub);
							});
						});
					} else {
						$callbacks[0]($q);
						$callbacks[1]($q);
					}
				}
			});
		}

		return $query;
	}

	protected function buildCondition($column, $val, $operation, $value) {
		return function ($q) use ($column, $val, $operation, $value) {
			if (str_contains($val, '.')) {
				[$relation, $relatedColumn] = explode('.', $val, 2);
				$relation = Str::camel($relation);

				$q->whereHas($relation, function ($relationQuery) use ($relatedColumn, $operation, $value, $relation) {
					$expression = $this->getVirtualColumnExpression($relatedColumn, $relation) ?? $relatedColumn;

					$this->applyCondition($relationQuery, $expression, $operation, $value);
				});

			} else {
				$expression = self::getVirtualColumnExpression($column) ?? $column;
				$this->applyCondition($q, $expression, $operation, $value);
			}
		};
	}

	protected function applyCondition($query, $expression, $operation, $value) {
		switch ($operation) {
			case 'equals':
				$query->where($expression, '=', $value);
				break;
			case 'not_equals':
				$query->where($expression, '!=', $value);
				break;
			case 'contains':
				$query->where($expression, 'LIKE', '%' . $value . '%');
				break;
			case 'starts_with':
				$query->where($expression, 'LIKE', $value . '%');
				break;
			case 'ends_with':
				$query->where($expression, 'LIKE', '%' . $value);
				break;
			case 'does_not_contain':
				$query->where($expression, 'NOT LIKE', '%' . $value . '%');
				break;
			case 'is_null':
				$query->whereNull($expression);
				break;
			case 'is_not_null':
				$query->whereNotNull($expression);
				break;
			case 'is_empty':
				$query->where(function ($q) use ($expression) {
					$q->whereNull($expression)->orWhere($expression, '');
				});
				break;
			case 'is_not_empty':
				$query->where(function ($q) use ($expression) {
					$q->whereNotNull($expression)->where($expression, '!=', '');
				});
				break;
			case 'greater_equal':
				$query->where($expression, '>', $value);
				break;
			case 'greater_than_or_equals':
				$query->where($expression, '>=', $value);
				break;
			case 'less_than':
				$query->where($expression, '<', $value);
				break;
			case 'less_than_or_equals':
				$query->where($expression, '<=', $value);
				break;
		}
	}

	protected function getVirtualColumnExpression($column, $relation=null) {
		$currentModel = Str::lower(last(explode('\\', get_class($this))));

		$filterModel = $relation??$currentModel;

		$virtualColumns = [
//			'user' => [
//				'name' => DB::raw("CONCAT(".COL_USER_FIRST_NAME.", ' ', ".COL_USER_LAST_NAME.")"),
//			],
//			'originUser' => [
//				'name' => DB::raw("CONCAT(".COL_USER_FIRST_NAME.", ' ', ".COL_USER_LAST_NAME.")"),
//			],
//			'targetUser' => [
//				'name' => DB::raw("CONCAT(".COL_USER_FIRST_NAME.", ' ', ".COL_USER_LAST_NAME.")"),
//			],
		];

		return isset($virtualColumns[$filterModel][$column])? $virtualColumns[$filterModel][$column] : null;
	}
}








