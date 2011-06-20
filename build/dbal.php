<?php

class dbal
{
    private $dbal;
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
            
            case 'sqlite':
                $details['dbtype'] = 'sqlite';
            break;
            
            case 'postgresql':
                $details['dbtype'] = 'postgresql';
            break;
        }
        
        try {
            $this->dbal = new PDO("{$details['dbtype']}:host={$details['hostname']};dbname={$details['dbname']}", $details['dbuser'], $details['dbpass']);
            $this->connected = true;
        } catch(PDOException $errmsg) {
            exit('PDO dbal Error: ' . $errmsg->getMessage());
            $this->connected = false;
        }
    }
    
    function prequery($sql, $query, $mode = PDO::FETCH_BOTH, $fetchAll = false, $additional = false)
    {
    
        if(!! $this->connected === false)
        {
            throw new Exception('PDO dbal: Not connected to database');
        }
    
        $dbQuery = $this->dbal->prepare($sql);
        $dbQuery->setFetchMode($mode);
        $dbQuery->execute($query);
        $fetchMethod = (!! $fetchAll === true) ? 'fetchAll' : 'fetch';
        $dbQuery = (!! $additional === true) ? array_merge($dbQuery->$fetchMethod(), array('count'  =>  $dbQuery->rowCount())) : $dbQuery->$fetchMethod();
        
        return $dbQuery;
    }
    
    function query($sql, $mode = PDO::FETCH_BOTH, $fetchAll = false)
    {
    
        if(!! $this->connected === false)
        {
            throw new Exception('PDO dbal: Not connected to database');
        }
    
        $dbQuery = $this->dbal->query($sql);
        $dbQuery->setFetchMode($mode);
        $fetchMethod = (!! $fetchAll === true) ? 'fetchAll' : 'fetch';
        $dbQuery = $dbQuery->$fetchMethod();
        return $dbQuery;
    }
}

?>