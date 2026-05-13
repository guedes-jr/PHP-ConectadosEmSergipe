<?php
$path = '/admin/dashboard';
$adminPath = str_replace('/admin', '', $path);
echo "Path: $path\n";
echo "AdminPath: $adminPath\n";
if (str_starts_with($path, '/admin/')) {
    echo "Starts with /admin/\n";
    if ($adminPath === '/dashboard') {
        echo "MATCHED /dashboard\n";
    } else {
        echo "NO MATCH /dashboard (AdminPath is '$adminPath')\n";
    }
} else {
    echo "DOES NOT start with /admin/\n";
}
