<?php

function isValidCategory($category) {
    // Check if the category is not empty and is within the length limit
    return !empty($category) && strlen($category) <= 50;
}

function isValidQuote($quote) {
    // Check if the quote is not empty and is within the length limit
    return !empty($quote) && strlen($quote) <= 255;
}

function isValidAuthor($author) {
    // Check if the author is not empty and is within the length limit
    return !empty($author) && strlen($author) <= 50;
}

?>
