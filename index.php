<?php

header('Content-Type: text/html; charset=UTF-8');

// Функция для сохранения введенных данных
function getPostValue($field) {
    return isset($_POST[$field]) ? htmlspecialchars($_POST[$field]) : '';
}

// Массив для хранения ошибок
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Показываем форму
    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Анкета</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>';
    
    if (!empty($_GET['save'])) {
        echo '<div class="success-message">Данные успешно сохранены</div>';
    }
    
    include('form.php');
    echo '</body></html>';
    exit();
}

// POST-запрос - обрабатываем форму
$fio = trim($_POST['fio'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$birthdate = $_POST['birthdate'] ?? '';
$gender = $_POST['gender'] ?? '';
$languages = $_POST['languages'] ?? [];
$bio = trim($_POST['bio'] ?? '');
$contract = isset($_POST['contract']) ? 1 : 0;

// 1. Проверка ФИО (только буквы, пробелы, дефис; не длиннее 150)
if (empty($fio)) {
    $errors['fio'] = 'ФИО обязательно для заполнения';
} elseif (strlen($fio) > 150) {
    $errors['fio'] = 'ФИО не должно превышать 150 символов';
} elseif (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s\-]+$/u', $fio)) {
    $errors['fio'] = 'ФИО может содержать только буквы, пробелы и дефис';
}


// 2. Проверка телефона (цифры, +, -, пробелы, скобки; минимум 5 символов)
if (empty($phone)) {
    $errors['phone'] = 'Телефон обязателен для заполнения';
} elseif (!preg_match('/^[\d\s\+\(\)\-]{5,20}$/', $phone)) {
    $errors['phone'] = 'Телефон должен содержать только цифры, +, -, пробелы и скобки (5-20 символов)';
}

// 3. Проверка email
if (empty($email)) {
    $errors['email'] = 'Email обязателен для заполнения';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Введите корректный email адрес';
}

// 4. Проверка даты рождения
if (empty($birthdate)) {
    $errors['birthdate'] = 'Дата рождения обязательна';
} else {
    $birthTimestamp = strtotime($birthdate);
    if ($birthTimestamp === false) {
        $errors['birthdate'] = 'Неверный формат даты';
    } elseif ($birthTimestamp > time()) {
        $errors['birthdate'] = 'Дата рождения не может быть в будущем';
    }
}

// 5. Проверка пола
if (empty($gender)) {
    $errors['gender'] = 'Выберите пол';
} elseif (!in_array($gender, ['male', 'female'])) {
    $errors['gender'] = 'Недопустимое значение пола';
}

// 6. Проверка языков программирования
if (empty($languages)) {
    $errors['languages'] = 'Выберите хотя бы один язык программирования';
} else {
    // Проверяем, что все ID языков существуют
    $validLangIds = range(1, 12); // Pascal=1 ... Go=12
    foreach ($languages as $lang) {
        if (!in_array($lang, $validLangIds)) {
            $errors['languages'] = 'Выбран недопустимый язык программирования';
            break;
        }
    }
}

// 7. Проверка чекбокса контракта
if (!$contract) {
    $errors['contract'] = 'Необходимо подтвердить ознакомление с контрактом';
}

// Если есть ошибки - показываем форму с ошибками
if (!empty($errors)) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Анкета - ошибки</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>';
    
    // Выводим ошибки
    echo '<div class="errors">';
    foreach ($errors as $error) {
        echo "<div class='error-message'>⚠️ $error</div>";
    }
    echo '</div>';
    
    include('form.php');
    echo '</body></html>';
    exit();
}


try {
    require_once __DIR__ . '/config.php';

    $db = new PDO(
	        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
		    DB_USER,
		        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Начинаем транзакцию
    $db->beginTransaction();
    
    // Вставляем основную запись
    $stmt = $db->prepare("
        INSERT INTO application
        (fio, phone, email, birthdate, gender, biography, contract)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $fio,
        $phone,
        $email,
        $birthdate,
        $gender,
        $bio,
        $contract
    ]);
    
    $app_id = $db->lastInsertId();
    
    // Вставляем выбранные языки
    $stmt = $db->prepare("
        INSERT INTO application_languages
        (application_id, language_id)
        VALUES (?, ?)
    ");
    
    foreach ($languages as $lang) {
        $stmt->execute([$app_id, $lang]);
    }
    
    // Подтверждаем транзакцию
    $db->commit();
    
    // Перенаправляем с сообщением об успехе
    header('Location: ?save=1');
    exit();
    
} catch (PDOException $e) {
    // Откатываем транзакцию при ошибке
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    
    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Ошибка</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="error-message">Ошибка базы данных: ' . htmlspecialchars($e->getMessage()) . '</div>
    </body></html>';
    exit();
}
?>
