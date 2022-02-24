<?php

if (isset($result) and count($result) > 0) {
    foreach ($result as $path) {
        echo "<div style='color: red;'>". $path . "<div>";
    }
}
