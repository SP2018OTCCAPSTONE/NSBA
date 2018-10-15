
<?php if ( ! empty($user->errors)): ?>
  <ul>
    <?php foreach ($user->errors as $error): ?>
      <li><?php echo $error; ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="post">
  <div>
    <label for="firstName">First Name</label>
    <input id="firstName" name="firstName" value="<?php echo htmlspecialchars($user->firstName); ?>" />
  </div>

  <div>
    <label for="lastName">Last Name</label>
    <input id="lastName" name="lastName" value="<?php echo htmlspecialchars($user->lastName); ?>" />
  </div>

  <div>
    <label for="email">email address</label>
    <input id="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>" />
  </div>

  <div>
    <label for="emailSecondary">Secondary email address</label>
    <input id="emailSecondary" name="emailSecondary" value="<?php echo htmlspecialchars($user->emailSecondary); ?>" />
  </div>

  <div>
    <label for="password">Password</label>
    <input type="password" id="password" name="password" />
    <p>Leave blank to keep current password</p>
  </div>

  <?php $is_same_user = $user->userId == Auth::getInstance()->getCurrentUser()->userId; ?>

  <div>
    <label for="is_active">
        <?php if ($is_same_user): ?>
            <input type="hidden" name="is_active" value="1" />
            <input type="checkbox" disabled="disabled" checked="checked" /> active

        <?php else: ?>
            <input id="is_active" name="is_active" type="checkbox" value="1"
                <?php if ($user->is_active): ?>checked="checked"<?php endif; ?>/> active

        <?php endif; ?>
    </label>
  </div>

  <div>
    <label for="is_admin">
        <?php if ($is_same_user): ?>
            <input type="hidden" name="is_admin" value="1" />
            <input type="checkbox" disabled="disabled" checked="checked" /> administrator

        <?php else: ?>
            <input id="is_admin" name="is_admin" type="checkbox" value="1"
                <?php if ($user->is_admin): ?>checked="checked"<?php endif; ?>/> administrator

        <?php endif; ?>
    </label>
  </div>

  <input type="submit" value="Save" />
  <a href="/NSBA/admin/users<?php if (isset($user->userId)) { echo '/show.php?userId=' . $user->userId; } ?>">Cancel</a>
</form>