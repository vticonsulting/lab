### General PHP

```php
array_push($x, $y); to $x[] = $y;
```

### Multiple databases

```php
// This is default database:
$db['default'] = array(
    // ...
    'database' => 'mydatabase',
);

// Add another database at the bottom of database.php file
$db['second'] = array(
  	// ...
    'database' => 'mysecond',
);
```

```php
// In autoload.php config file
$autoload['libraries'] = array('database', 'email', 'session');
```

```php

// The default database is worked fine by autoload the database library
// but second database load and connect by using constructor
// in model and controller...
class Second_db_model extends CI_Model {
    function __construct(){
        parent::__construct();
        // load our second db and put in $db2
        $this->db2 = $this->load->database('second', TRUE);
    }

    public function getsecondUsers() {
        $query = $this->db2->get('members');

        return $query->result();
    }
}
```
