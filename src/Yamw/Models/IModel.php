<?php
namespace Yamw\Models;

use \Yamw\Lib\Interfaces\ModelInterface;
use \Yamw\Lib\MySql\MySql;
use \Yamw\Lib\MySql\AdvMySql;

/**
 * It's like an interface, but with functions you really need for Model-functionality (actually ORM)
 * @author Anh Nhan
 * @deprecated
 */
abstract class IModel implements ModelInterface
{
    private $data = array();
    protected $MySql;
    protected $autogenerate_methods = false;
    protected $id_column = 'id';
    protected $init = false;

    final public function __construct($data)
    {
        $this->data = $data;
        global $MySql;
        $this->MySql =& $MySql;
        
        if ($this->autogenerate_methods) {
            foreach ($this->data as $name => $content) {
                $fname = 'get'.functionify($name);

                $this->$fname = create_function('', 'return $this->Get("'.$name.'");');
            }
        }

        if ($this->init) {
            $this->init();
        }
    }

    final public function getData()
    {
        return $this->data;
    }

    /**
     * I'm not sure if this is even being used anywhere...
     * @param unknown_type $x
     * @return unknown_type
     */
    final protected function initData($data)
    {
        $temp1 = $this->MySql->findSingleRow($data, array('id' => $this->getId()))->getData();
        $this->data = $temp1[0];
    }

    /**
     * Warning: Overwrites $this->data
     * @param array $val The value to replace $this->data
     * @return nothing
     */
    final public function setData(array $val)
    {
        $this->data = $val;
    }

    final public function getTable()
    {
        return $this->table;
    }

    final public function getId()
    {
        return $this->Get($this->id_column);
    }

    final public function setId($val)
    {
        $this->Set($this->id_column, $val);
    }

    final protected function get($name)
    {
        if (!$name || !is_string($name)) {
            return false;
        }

        global $Config;

        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            return $this->data[ColumnName($Config->mysql_table_prefix.$this->getTable(), $name)];
        }
    }

    final protected function set($name, $value)
    {
        if (!$name || !$value || !is_string($name)) {
            return false;
        }

        global $Config;
        if (isset($this->data[ColumnName($Config->mysql_table_prefix.$this->getTable(), $name)])) {
            $this->data[ColumnName($Config->mysql_table_prefix.$this->getTable(), $name)] =
            mb_convert_encoding($value, "UTF-8");
        } else {
            $this->data[$name] = mb_convert_encoding($value, "UTF-8");
        }
    }

    final public function bbcodeCache($field, $data = '')
    {
        if ($data) {
            $this->Set('cached_'.$field, BBCode2HTML($data));
        } else {
            $this->Set('cached_'.$field, BBCode2HTML($this->Get($field)));
        }

        return $this->save();
    }

    final public function forumCache($content)
    {
        // First check if entry already exists in db.
        $query = AdvMySql::getTable('forum_cache')
            ->select('id')
            ->where('id', $this->getId())
            ->execute();
        
        if (!count($query)) { // If it does not exist, create one
            $this->Set('cached_content', BBCode2HTML($content));
            return AdvMySql::insertTable('forum_cache')
                ->insertData('id', $this->getId())
                ->insertData('cached_content', $this->Get('cached_content'), 'text')
                ->insertData('last_modified', time())
                ->execute();
        } else { // Else update the existing entry
            $data = array('id' => $this->getId(),
                            'cached_content' => BBCode2HTML($content),
                            'last_modified' => time());
            return $this->MySql->updateData('hcaw_forum_cache', $data, $this->getId());
        }
    }
    
    final public function cache($cache)
    {
        if ($cache != 'none') {
            switch ($cache) {
                case 'forum':
                    break;
                default:
                    $data['cached_'.$cache] = BBCode2HTML($this->data[$cache]);
                    break;
            }
        }
    }

    final public function save($cache = false)
    {
        foreach ($this->data as &$value) {
            $value = mb_convert_encoding($value, "UTF-8");
        }
        
        if ($cache) {
            $this->cache($cache);
        }
        
        $new_data = array();
        foreach ($this->data as $key => &$value) {
            if (strpos($key, DB_TB_DELIMITER)) {
                $tmp = explode(DB_TB_DELIMITER, $key);
                $new_data[$tmp[1]] = $value;
            } else {
                $new_data[$key] = $value;
            }
        }

        return MySql::getInstance()->updateData($this->getTable(), $new_data, $this->getId());
    }

    final public function createNew($table, $data, $cache_field = null)
    {
        if (!is_array($data) || (!$table && !is_string($table))) {
            return false;
        }

        if ($cache_field && is_string($cache_field)) {
            $data['cached_'.$cache_field] = BBCode2HTML($data[$cache_field]);
        }

        $ret = MySql::getInstance()->insertData($table, $data);

        return $ret;
    }

    final public function createEntry($cache = 'none')
    {
        $this->cache($cache);
        $ret = MySql::getInstance()->insertData($this->getTable(), $this->getData());
        return $ret;
    }
}
