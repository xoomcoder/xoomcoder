<?php

extract(Response::$contents["dbline"] ?? []);

echo "($id,$title)";

?>