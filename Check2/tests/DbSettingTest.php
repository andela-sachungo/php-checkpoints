<?php 

namespace Stacey\Potatotests;

use Stacey\Potato\Models\DbQuerries;

class DbSettingTest
{
    public static function createTable($tableName)
    {
        $connDetails = array(
            'host' => 'localhost',
            'dbname' => 'potatorm',
            'user' => 'andela',
            'pass' => 'andela'
            );
        $connect = DbQuerries::connect($connDetails);

        try {
            $connect->exec("DROP TABLE IF EXISTS {$tableName}");
            $connect->exec(
                "CREATE TABLE {$tableName}
				(
					id              int unsigned NOT NULL auto_increment, 
					name            varchar(255) NOT NULL,                
					color           varchar(255) NOT NULL,                
					price           varchar(50) NOT NULL, 
					             
	                PRIMARY KEY     (id)
				)"
            );
            $connect->exec(
                "INSERT INTO {$tableName} (name, color, price)
				 VALUES ('Cadillac', 'aubergine', '$385,000')"
                );
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function dropTable($tableName)
    {
        $connDetails = array(
            'host' => 'localhost',
            'dbname' => 'potatorm',
            'user' => 'andela',
            'pass' => 'andela'
            );
        $connect = DbQuerries::connect($connDetails);

        try {
            $connect->exec("DROP TABLE IF EXISTS {$tableName}");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
