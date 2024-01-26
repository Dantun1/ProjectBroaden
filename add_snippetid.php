<?php
session_start();

$snippetId = $_GET['snippetId'];
$_SESSION['snippetIds'][$_SESSION['current_index']+1] = $snippetId;
$_SESSION['viewrequests'] ++;

// Making so buttons add snippet id into snippetids variable.
// Next post needs to go through priority queue and when it gets to end current index bigger than equal to count snippetids, say youve viewed all articles
// if counter less than 0 do nothing no error.
