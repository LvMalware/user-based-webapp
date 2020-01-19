<?php

    class SQLiteDB extends SQLite3
    {
        function __construct()
        {
            $this->open("db/mydatabase.db");
        }
    }

    function clear_cookies()
    {
        header_remove('Set-Cookie');
    }

    function remove_duplicate_cookies()
    {
        if (headers_sent())
        {
            return FALSE;
        }

        $cookies = array();

        foreach (headers_list() as $header)
        {
            if (strpos($header, 'Set-Cookie:') == 0)
            {
                $cookies[] = $header;
            }
        }
        
        clear_cookies();

        foreach (array_unique($cookies) as $cookie)
        {
            header($cookie, FALSE);
        }

        return TRUE;
    }
    
?>