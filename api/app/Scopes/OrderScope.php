<?php
/**
 * Global order scope
 *
 * @package OrderScope
 * @author kevin olson <acidjazz@gmail.com>
 * @version 0.1
 * @copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 * @license APACHE
 */

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OrderScope implements Scope
{

    private $column;
    private $direction;

    private $column_b;
    private $direction_b;

    public function __construct($column, $direction = 'asc',$column_b=false,$direction_b=false)
    {
        $this->column = $column;
        $this->direction = $direction;
        $this->column_b = $column_b;
        $this->direction_b = $direction_b;
    }

    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy($this->column, $this->direction);
        if ($this->column_b) {
            $builder->orderBy($this->column_b, $this->direction_b);
        }
    }
}
