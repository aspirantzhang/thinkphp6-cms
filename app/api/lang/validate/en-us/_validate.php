<?php

return [
    "validate" => [
        "require" => "{:field} should not be empty.",
        "number" => "{:field} must be numbers only.",
        "length" => "{:field} length should be between {:option}.",
        'checkParentId' => '{:field} should not be itself.',
        "numberArray" => "Invalid value: {:field} (Number Array)",
        "numberTag" => "Invalid value: {:field} (Number Tag)",
        "dateTimeRange" => "Invalid value: {:field} (DateTime Range)",
    ],
];
