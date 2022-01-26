<?php

return [
    "admin@admin_name#require" => "Admin Name should not be empty.",
    "admin@admin_name#length:6,32" => "Admin Name length should be between 6 - 32.",
    "admin@password#require" => "Password should not be empty.",
    "admin@password#length:6,32" => "Password length should be between 6 - 32.",
    "admin@display_name#length:4,32" => "Display name length should be between 4 - 32.",
    "admin@groups#numberArray" => "Invalid value: Groups",
];
