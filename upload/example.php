<?php
// Open the file for reading
if (($h = fopen("files/ejemplo.csv", "r")) !== FALSE) 
{
  // Convert each line into the local $data variable
  while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
  {		
    echo 'Nombre: '.$data[0].' Apellido='.$data[1].' Edad='.$data[2]. "\n";
  }

  // Close the file
  fclose($h);
}