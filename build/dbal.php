<?php

class dbal
{
    public $dbal;
    public $last_query = array();
    private $connected = false;
    
    function dbal($details = null)
    {
        if($details)
        {
            $this->dbal_connect($details);
        }
    }
    
    function dbal_connect($details)
    {
        switch($details['dbtype'])
        {
            default:
            case 'mysql':
                $details['dbtype'] = 'mysql';
            break;
            
            case 'odbc':
                $details['dbtype'] = 'odbc';
            break;
            
            case 'mssql':
                $details['dbtype'] = 'mssql';
            break;
            
            case 'sqlite':
                $details['dbtype'] = 'sqlite';
            break;
            
            case 'postgre':
            case 'postgresql':
                $details['dbtype'] = 'postgresql';
            break;
            
            case 'firebird':
                $details['dbtype'] = 'firebird';
            break;
        }
        
        try {
            $details['dbname'] = ($details['dbtype'] != 'firebird') ? ";dbname={$details['dbname']}" : '';
            $this->dbal = new PDO("{$details['dbtype']}:host={$details['hostname']}{$details['dbname']}" , $details['dbuser'], $details['dbpass']);
            $this->connected = true;
        } catch(PDOException $errmsg) {
            $this->connected = false;
            exit('PDO dbal Error: ' . $errmsg->getMessage());
        }
    }
    
    function prequery($sql, $query, $fetchMode = PDO::FETCH_BOTH, $fetchType = 'fetch', $additional = false)
    {
    
        if(!! $this->connected === false)
        {
            throw new PDOException('PDO dbal: Not connected to database');
        }
        
        if(!is_array($query))
        {
            throw new PDOException('PDO dbal: Query should be array');
        }
        
        $dbQuery = $this->dbal->prepare($sql);
        $dbQuery->setFetchMode($fetchMode);
        $dbQuery->execute($query);
        
        if(!method_exists($this->dbal->prepare($sql), $fetchType))
        {
            throw new PDOException("PDO dbal: There is no such PDO fetch type ({$fetchType}))");
        }
        
        $fetchMethod = (isset($fetchType)) ? $fetchType : 'fetch';
        $dbQuery = (!! $additional === true) ? array_merge($dbQuery->$fetchMethod(), array('__rows'  =>  $dbQuery->rowCount(), '__columns'  =>  $dbQuery->columnCount())) : $dbQuery->$fetchMethod();
        $this->last_query = $dbQuery;
        
        return $dbQuery;
    }
    
    function query($sql, $fetchMode = PDO::FETCH_BOTH, $fetchType = 'fetch', $additional = false)
    {
    
        if(!! $this->connected === false)
        {
            throw new PDOException('PDO dbal: Not connected to database');
        }
        
        $dbQuery = $this->dbal->query($sql);
        $dbQuery->setFetchMode($fetchMode);
        
        if(!method_exists($this->dbal->query($sql), $fetchType))
        {
            throw new PDOException("PDO dbal: There is no such PDO fetch type ({$fetchType}))");
        }
        
        $fetchMethod = (isset($fetchType)) ? $fetchType : 'fetch';
        $dbQuery = (!! $additional === true) ? array_merge($dbQuery->$fetchMethod(), array('__rows'  =>  $dbQuery->rowCount(), '__columns'  =>  $dbQuery->columnCount())) : $dbQuery->$fetchMethod();
        $this->last_query = $dbQuery;
        
        return $dbQuery;
    }
    
    function fetchsize()
    {
        if(isset($this->last_query['__rows']) && isset($this->last_query['__columns']))
        {
            return array(
                'rows'    =>    $this->last_query['__rows'],
                'columns'    =>    $this->last_query['__columns'],
            );
        }
        return;
    }
}

?>