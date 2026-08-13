<?php

session_start();

session_unset();
session_destroy();

echo "Đã xóa session. Hãy quay lại trang Login.";