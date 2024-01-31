<?php
session_start();

// File adds a snippet ID to the session array of articles when a user clicks on a button to view a specific article from their bookmarked/liked articles.

$snippetId = $_GET['snippetId'];
$_SESSION['snippetIds'][$_SESSION['current_index']+1] = $snippetId;
$_SESSION['viewrequests'] ++;
