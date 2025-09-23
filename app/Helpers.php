<?php

function thousand($number)
{
    if ($number != 0) {
        return number_format($number, 0, ',', '.');
    }
}
