#!/bin/bash

bootstrap=$(cat <<- 'PHP'
function env(string $name): string
  {
      $result = getenv($name);
      if ($result === false || $result == "") {
          ob_end_clean();
          echo "No value for variable $name\n";
          die(1);
      }
      return $result;
  }

ob_start();
require $argv[1];

PHP
)

parsefile () {
  local outputfile=${1:0:-4}
  local output
  echo "Parsing template $1, will store it in $outputfile";


  output=$(php -r "$bootstrap" $1)
  if [ $? -ne 0 ]; then
    echo "Template parsing failed: $output"
    exit 1
  else
    echo "$output" > $outputfile
  fi



}

parsedir () {
  echo "Parsing templates in dir $1";
}

for var in "$@"; do
  parsefile "$var"
done
