<?php 

// echo phpinfo();

?>

<?php
// $command = 'ls -l';

$command = 'rm -rf application/cache/temp';
$output = [];
$return_var = 0;

// Execute the command
exec($command, $output, $return_var);

// Output the result
echo "Command Output:\n";
echo "<pre>";
print_r($output);

echo "\nReturn Status: $return_var";


?>