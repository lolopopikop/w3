<div class="container">

<?php if (!empty($errors)): ?>
    <div class="error-summary">
        <strong>Пожалуйста, исправьте следующие ошибки:</strong>
    </div>
<?php endif; ?>

<form action="index.php" method="POST">

    <div class="form-group">
        <label for="fio">ФИО:</label>
        <input type="text" id="fio" name="fio" value="<?php echo isset($_POST['fio']) ? htmlspecialchars($_POST['fio']) : ''; ?>" class="<?php echo isset($errors['fio']) ? 'error' : ''; ?>">
        <?php if (isset($errors['fio'])): ?>
            <div class="field-error"><?php echo $errors['fio']; ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="phone">Телефон:</label>
        <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" class="<?php echo isset($errors['phone']) ? 'error' : ''; ?>">
        <?php if (isset($errors['phone'])): ?>
            <div class="field-error"><?php echo $errors['phone']; ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" class="<?php echo isset($errors['email']) ? 'error' : ''; ?>">
        <?php if (isset($errors['email'])): ?>
            <div class="field-error"><?php echo $errors['email']; ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="birthdate">Дата рождения:</label>
        <input type="date" id="birthdate" name="birthdate" value="<?php echo isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : ''; ?>" class="<?php echo isset($errors['birthdate']) ? 'error' : ''; ?>">
        <?php if (isset($errors['birthdate'])): ?>
            <div class="field-error"><?php echo $errors['birthdate']; ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Пол:</label>
        <div class="radio-group">
            <label class="radio-label">
                <input type="radio" name="gender" value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'male') ? 'checked' : ''; ?>> Мужской
            </label>
            <label class="radio-label">
                <input type="radio" name="gender" value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'female') ? 'checked' : ''; ?>> Женский
            </label>
        </div>
        <?php if (isset($errors['gender'])): ?>
            <div class="field-error"><?php echo $errors['gender']; ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Любимый язык программирования:</label>
        <select name="languages[]" multiple size="5" class="<?php echo isset($errors['languages']) ? 'error' : ''; ?>">
            <?php
            // Получаем выбранные языки из POST (если есть)
            $selected_langs = isset($_POST['languages']) ? $_POST['languages'] : [];
            ?>
            <option value="1" <?php echo (in_array('1', $selected_langs)) ? 'selected' : ''; ?>>Pascal</option>
            <option value="2" <?php echo (in_array('2', $selected_langs)) ? 'selected' : ''; ?>>C</option>
            <option value="3" <?php echo (in_array('3', $selected_langs)) ? 'selected' : ''; ?>>C++</option>
            <option value="4" <?php echo (in_array('4', $selected_langs)) ? 'selected' : ''; ?>>JavaScript</option>
            <option value="5" <?php echo (in_array('5', $selected_langs)) ? 'selected' : ''; ?>>PHP</option>
            <option value="6" <?php echo (in_array('6', $selected_langs)) ? 'selected' : ''; ?>>Python</option>
            <option value="7" <?php echo (in_array('7', $selected_langs)) ? 'selected' : ''; ?>>Java</option>
            <option value="8" <?php echo (in_array('8', $selected_langs)) ? 'selected' : ''; ?>>Haskell</option>
            <option value="9" <?php echo (in_array('9', $selected_langs)) ? 'selected' : ''; ?>>Clojure</option>
            <option value="10" <?php echo (in_array('10', $selected_langs)) ? 'selected' : ''; ?>>Prolog</option>
            <option value="11" <?php echo (in_array('11', $selected_langs)) ? 'selected' : ''; ?>>Scala</option>
            <option value="12" <?php echo (in_array('12', $selected_langs)) ? 'selected' : ''; ?>>Go</option>
        </select>
        <?php if (isset($errors['languages'])): ?>
            <div class="field-error"><?php echo $errors['languages']; ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="bio">Биография:</label>
        <textarea id="bio" name="bio"><?php echo isset($_POST['bio']) ? htmlspecialchars($_POST['bio']) : ''; ?></textarea>
    </div>

    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="contract" <?php echo (!empty($_POST['contract'])) ? 'checked' : ''; ?>> С контрактом ознакомлен
        </label>
        <?php if (isset($errors['contract'])): ?>
            <div class="field-error"><?php echo $errors['contract']; ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <button type="submit">Сохранить</button>
    </div>

</form>

</div>
