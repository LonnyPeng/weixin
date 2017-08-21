<?php
namespace app\admin\model;

use think\Model;

class Common extends Model
{
	protected $initSql = ["setJoin", "setWhere", "setGroupBy", "setOrderBy", "setLimit"];

	protected function setTable($table = [])
	{
		if (!$table) {
			return false;
		}

		if (is_array($table) && count($table) > 0) {
			$table = ' ' . implode(',', $table);
		} elseif(is_string($table) && '' != $table) {
			$table = ' ' . $table;
		}

		return $table;
	}

	protected function setFile($file = [])
	{
		if (!$file) {
			return " *";
		}

		if (is_array($file) && count($file) > 0) {
			$file = ' ' . implode(',', $file);
		} elseif(is_string($file) && '' != $file) {
			$file = ' ' . $file;
		}

		return $file;
	}

	protected function setSql($sql, $initSql = [])
	{
		if (!$sql) {
			return false;
		}

		foreach ($this->initSql as $value) {
			if (isset($initSql[$value])) {
				$sql = $this->$value($sql, $initSql[$value]);
			}
		}

		return $sql;
	}

	protected function setJoin($sql = "", $join = [])
	{
	    if (!$sql) {
	        return false;
	    }

	    if (is_array($join) && count($join) > 0) {
	        $sql .= ' ' . implode(' ', $join);
	    } elseif (is_string($join) && '' != $join) {
	        $sql .= ' ' . $join;
	    }

	    return $sql;
	}

	protected function setWhere($sql = "", $where = [])
	{
	    if (!$sql) {
	        return false;
	    }

	    if (is_array($where) && count($where) > 0) {
	        $sql .= ' WHERE ' . implode(' AND ', $where);
	    } elseif (is_string($where) && '' != $where) {
	        $sql .= ' WHERE ' . $where;
	    }

	    return $sql;
	}

	protected function setGroupBy($sql = "", $groupBy = [])
	{
		if (!$sql) {
			return false;
		}

		if (is_array($groupBy) && count($groupBy) > 0) {
		    $sql .= ' GROUP BY ' . implode(',', $groupBy);
		} elseif (is_string($groupBy) && '' != $groupBy) {
		    $sql .= ' GROUP BY ' . $groupBy;
		}

		return $sql;
	}

	protected function setOrderBy($sql = "", $orderBy = [])
	{
	    if (!$sql) {
	        return false;
	    }

	    if (is_array($orderBy) && count($orderBy) > 0) {
	        $sql .= ' ORDER BY ' . implode(',', $orderBy);
	    } elseif (is_string($orderBy) && '' != $orderBy) {
	        $sql .= ' ORDER BY ' . $orderBy;
	    }

	    return $sql;
	}

	protected function setLimit($sql = "", $limit = [])
	{
	    if (!$sql) {
	        return false;
	    }

	    if (is_array($limit) && count($limit) > 0 && count($limit) < 3) {
	        $sql .= ' LIMIT ' . implode(',', $limit);
	    } elseif (is_string($limit) && '' != $limit) {
	        $sql .= ' LIMIT ' . $limit;
	    }

	    return $sql;
	}
}