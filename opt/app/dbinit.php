<?php

require_once __DIR__.'/../../vendor/autoload.php';

use r\Exceptions\{RqlException, RqlServerError};


$conn = r\connect('rdb', 28015);

try {
    r\dbCreate('plank')->run($conn);
    print_r(r\dbList()->run($conn));
    echo "\n";
} catch (RqlServerError $e) {
    echo $e."\n";
} catch (\Exception $e) {
    echo $e->getMessage();
}

$db = r\db('plank');

/**
 * @todo create indices
 */
try {
    $db->tableCreate('users')->run($conn);
} catch (RqlServerError $e) {
    echo $e."\n";
} catch (\Exception $e) {
    echo $e->getMessage();
}
try {
    $db->tableCreate('boards')->run($conn);
} catch (RqlServerError $e) {
    echo $e."\n";
} catch (\Exception $e) {
    echo $e->getMessage();
}
try {
    $db->tableCreate('tasks')->run($conn);
} catch (RqlServerError $e) {
    echo $e."\n";
} catch (\Exception $e) {
    echo $e->getMessage();
}
try {
    $db->tableCreate('tags')->run($conn);
} catch (RqlServerError $e) {
    echo $e."\n";
} catch (\Exception $e) {
    echo $e->getMessage();
}

print_r($db->tableList()->run($conn));
echo "\n";

echo "done!\n";
