<?php
namespace Yamw\Lib\MySql;

class AdvMySql_getTable extends SuperAdvMySql
{
    protected $selects = array();
    private $tables = array();
    private $orders = array();
    private $groups = array();
    private $left_joins = array();
    private $right_joins = array();
    private $wheres = array();
    private $distinct = false;
    private $prependtablename = true;
    private $limit = array('start' => 0, 'end' => null);
    private $natural_join = false;

    private $query_select;
    private $query_table;
    private $query_natural_join;
    private $query_left_join;
    private $query_order;
    private $query_groups;
    private $query_where;
    private $query_limit;

    public function where($attr, $val = '', $type = '=')
    {
        switch (strtolower($attr)) {
            case 'and':
                $this->wheres[] = array('attr' => 'AND', 'val' => 'AND');
                break;
            case 'or':
                $this->wheres[] = array('attr' => 'OR', 'val' => 'OR');
                break;
            default:
                switch ($val) {
                    case null:
                        $this->wheres[] = array('attr' => $attr, 'val' => 'null', 'type' => $type);
                        break;
                    default:
                        if (!is_array($val)) {
                            $this->wheres[] = array('attr' => $attr, 'val' => "'{$val}'", 'type' => $type);
                        } else {
                            foreach ($val as $x => $y) {
                                $this->wheres[] = array('attr' => $attr, 'val' => "'{$y}'", 'type' => $type);
                                if ( $x+1 < count($val) ) {
                                    $this->addOr();
                                }
                            }
                        }
                        break;
                }
                break;
        }

        return $this;
    }

    /**
     * Adds an _AND_ condition to the query's select statement.
     */
    public function addAnd()
    {
        return $this->where('and');
    }

    /**
     * Adds an _OR_ condition to the query's select statement.
     */
    public function addOr()
    {
        return $this->where('or');
    }

    /**
     * Adds an _DISTINCT_ condition to the query's select statement.
     */
    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function doNotPrependTableName()
    {
        $this->prependtablename = false;
        return $this;
    }

    public function select($val)
    {
        if (strpos($val, ',')) {
            $temp = explode(',', $val);
            foreach ($temp as &$value) {
                $value = trim($value);
            }
        } else {
            $temp = array(trim($val));
        }
        $this->selects = array_merge($this->selects, $temp);
        return $this;
    }

    public function orderby($attr, $dir = DESC)
    {
        $this->orders[$attr] = $dir;

        return $this;
    }

    public function groupby($attr)
    {
        $this->groups[] = $attr;

        return $this;
    }

    public function limit($start, $end = null)
    {
        $this->limit = array('start' => (int)$start, 'end' => (int)$end);

        return $this;
    }

    public function leftJoin($table, $condition)
    {
        if ($this->prependtablename) {
            $table = $this->pref . $table;
        }

        $this->left_joins[] = array('table' => $table, 'cond' => $condition);

        return $this;
    }

    public function naturalJoin($t)
    {
        $this->natural_join = $t;

        return $this;
    }

    public function generateQuery()
    {
        $this->generateSelect();
        $this->generateFrom();
        $this->generateWhere();
        $this->generateOrderBy();
        $this->generateGroupBy();
        $this->generateLimit();
        $this->generateLeftJoin();

        if ($this->natural_join) {
            $this->query_natural_join = ' NATURAL JOIN '.$this->natural_join.' ';
        }

        $this->query = $this->query_select .
            $this->query_table .
            $this->query_natural_join .
            $this->query_left_join .
            $this->query_where .
            $this->query_groups .
            $this->query_order .
            $this->query_limit;

        $this->query = str_replace(", ::", "", $this->query);
        $this->query = str_replace(",\n ::", "", $this->query);
        $this->query = str_replace(",\n::", "", $this->query);
        $this->query = str_replace(" ::", "", $this->query);
        $this->query = str_replace("::", "", $this->query);
        $this->query = str_replace(", AND", " AND", $this->query);
        $this->query = str_replace(", OR", " OR", $this->query);

        return $this;
    }

    private function generateSelect()
    {
        global $Config;
        if ($this->selects) {
            if (count($this->selects) == 1) {
                foreach ($this->selects as $select) {
                    $this->query_select = $select;
                }
            } else {
                foreach ($this->selects as $select) {
                    $aselect = (strpos($select, $this->table)) ? $select :
                        ($this->prependtablename ? $this->table.'.'.$select : $select);
                    $this->query_select .= $aselect.', ';
                }
                $this->query_select .= '::';
            }
        } else {
            $this->query_select = '*';
        }

        $distinct = ($this->distinct) ? 'DISTINCT ' : '';
        $this->query_select = 'SELECT '.$distinct.$this->query_select.' ';
    }

    private function generateFrom()
    {
        if ($this->tables) {
            if (count($this->tables) == 1) {
                foreach ($this->tables as $select) {
                    $this->query_table = $select;
                }
            } else {
                foreach ($this->tables as $select) {
                    $this->query_table .= $select.', ';
                }
                $this->query_table .= '::';
            }
        } else {
            $this->query_table = "\nFROM ".$this->table.' ';
        }
    }

    private function generateWhere()
    {
        if ($this->wheres) {
            if (count($this->wheres) == 1) {
                foreach ($this->wheres as $where) {
                    $this->query_where = $where['attr'].' '.$where['type'].' '.$where['val'];
                }
            } else {
                foreach ($this->wheres as $where) {
                    switch ($where['attr']) {
                        case 'AND':
                            $this->query_where .= 'AND ';
                            break;
                        case 'OR':
                            $this->query_where .= 'OR ';
                            break;
                        default:
                            $this->query_where .= $where['attr'].' '.
                                $where['type'].' '.$where['val'].', ';
                            break;
                    }
                }
            }

            $this->query_where .= '::';
            $this->query_where = "\nWHERE ".$this->query_where;
        } else {
            $this->query_where = '';
        }
        $this->query_where.=' ';
    }

    private function generateOrderBy()
    {
        global $Config;
        if ($this->orders) {
            if (count($this->orders) == 1) {
                foreach ($this->orders as $key => $order) {
                    if (preg_match('/^mybb_/', $key)) {
                        $key = (strpos($key, $Config->mysql_table_prefix) &&
                            !$this->prependtablename) ? $key : $this->table.'.'.$key;
                    }
                    $this->query_order = $key.' '.$order;
                }
            } else {
                foreach ($this->orders as $key => $order) {
                    if (preg_match('/^mybb_/', $key)) {
                        $key = (strpos($key, $Config->mysql_table_prefix) &&
                            !$this->prependtablename) ? $key : $this->table.'.'.$key;
                    }
                    $this->query_order .= $key.' '.$order.', ';
                }
                $this->query_order .= '::';
            }
            $this->query_order = "\nORDER BY ".$this->query_order;
        } else {
            $this->query_order = '';
        }
        $this->query_order.=' ';
    }


    private function generateGroupBy()
    {
        if ($this->groups) {
            if (count($this->groups) == 1) {
                foreach ($this->groups as $key => $order) {
                    $this->query_groups = $order;
                }
            } else {
                foreach ($this->groups as $key => $order) {
                    $this->query_groups .= $order.', ';
                }
                $this->query_groups .= '::';
            }
            $this->query_groups = "\nGROUP BY ".$this->query_groups;
        } else {
            $this->query_groups = '';
        }
        $this->query_groups.=' ';
    }

    private function generateLimit()
    {
        if ($this->limit['start'] != false || $this->limit['start'] != null) {
            if ($this->limit['end']) {
                $this->query_limit = "\nLIMIT ".$this->limit['start'].', '.$this->limit['end'];
            } else {
                $this->query_limit = "\nLIMIT 0, ".($this->limit['start']);
            }
        } else {
            $this->query_limit = '';
        }
        $this->query_limit.=' ';
    }

    private function generateLeftJoin()
    {
        if ($this->left_joins) {
            $this->query_left_join = "\nLEFT JOIN (";
            foreach ($this->left_joins as $key => $value) {
                $this->query_left_join .= $value['table'].', ';
            }

            $this->query_left_join .= ') ON (';

            foreach ($this->left_joins as $key => $value) {
                $this->query_left_join .= $value['cond'].', ';
            }

            $this->query_left_join .= ')';

            $this->query_left_join = str_replace(', )', ')', $this->query_left_join);
        } else {
            $this->query_left_join = '';
        }
        $this->query_left_join.=' ';
    }
}
