
<?php if ( ! empty($user->errors)): ?>
  <ul>
    <?php foreach ($user->errors as $error): ?>
      <li><?php echo $error; ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<?php
$prices = User::getMemberTypeData();
//var_dump($prices);
$json_array = json_encode($prices);
$type = isset($user->member_type_id) ? $user->member_type_id : "0";
$meal = isset($user->meals) ? $user->meals : "0";
// echo($type);
// echo($meal);
?>

<script>
var priceArray = <?php echo $json_array; ?>;
var membershipType = <?php echo $type; ?>;
var mealAmt = <?php echo $meal; ?>;
</script>

<form method="POST" enctype="multipart/form-data">

  <div>
    <input id="currentPage" name="currentPage" type="hidden" value="<?php echo $current; ?>" />
  </div>

  <div>
    <label for="firstName">First Name</label>
    <input id="firstName" name="firstName" value="<?php echo htmlspecialchars($user->first_name); ?>" />
  </div>

  <div>
    <label for="lastName">Last Name</label>
    <input id="lastName" name="lastName" value="<?php echo htmlspecialchars($user->last_name); ?>" />
  </div>

  <div>
    <label for="email1">Primary email address </label>
    <input id="email1" name="email1" value="<?php echo htmlspecialchars($user->email_1); ?>" />
    <p>This will be member's username and must be unique</p>
  </div>

  <div>
    <label for="email2">Other email address</label>
    <input id="email2" name="email2" value="<?php echo htmlspecialchars($user->email_2); ?>" />
  </div>

  <div>
    <label for="line1">Address line 1</label>
    <input id="line1" name="line1" value="<?php echo htmlspecialchars($user->line_1); ?>" />
  </div>

  <div>
    <label for="line2">Address line 2</label>
    <input id="line2" name="line2" value="<?php echo htmlspecialchars($user->line_2); ?>" />
  </div>

  <div>
    <label for="city">City</label>
    <input id="city" name="city" value="<?php echo htmlspecialchars($user->city); ?>" />
  </div>

  <div>
    <label for="state">State</label>
    <input id="state" name="state" value="<?php echo htmlspecialchars($user->state); ?>" />
  </div>

  <div>
    <label for="zip">Zip</label>
    <input id="zip" name="zip" value="<?php echo htmlspecialchars($user->zip); ?>" />
  </div>

  <div>
    <label for="company">Company Name</label>
    <input id="company" name="company" value="<?php echo htmlspecialchars($user->company); ?>" />
  </div>

  <div>
    <label for="workPhone">Work Phone</label>
    <input id="workPhone" name="workPhone" value="<?php echo htmlspecialchars($user->work_phone); ?>" />
  </div>

  <div>
    <label for="cellPhone">Cell Phone</label>
    <input id="cellPhone" name="cellPhone" value="<?php echo htmlspecialchars($user->cell_phone); ?>" />
  </div>

  <div>
    <label for="fax">Fax</label>
    <input id="fax" name="fax" value="<?php echo htmlspecialchars($user->fax); ?>" />
  </div>

  <div>
    <label for="website">Website Url</label>
    <input id="website" name="website" value="<?php echo htmlspecialchars($user->website); ?>" />
  </div>

  <div>
    <label for="referredBy">Referred By</label>
    <input id="referredBy" name="referredBy" value="<?php echo htmlspecialchars($user->referred_by); ?>" />
  </div>

  <!-- DISABLED - WIP -->
  <!--<form action="" method="POST" enctype="multipart/form-data">-->
    <div>
      <label for="image">Image Upload</label>
      <input id="image" name="sub" type="file" disabled="disabled" value="<?php echo htmlspecialchars($user->image); ?>" />
      <input type="submit" value="Upload File" name="image" disabled="disabled">
    </div>
  <!--</form>-->

  <div>
    <label for="memberType">Membership Type</label>
    <select id="memberType" name="memberType" required="required">
      <option value="" selected disabled hidden>Select a Membership Type</option>
      <option value="1">Non-Member User</option>
      <option value="2">Gratis</option>
      <option value="3">Individual</option>
      <option value="4">Retired</option>
      <option value="5">Neighborhood Association</option>
      <option value="6">Corporate 2</option>
      <option value="7">Corporate 3</option>
      <option value="8">Corporate Associate (Sub-Membership)</option>
    </select>
  </div>

  <div>
    <label for="meals">Pre-Paid Meals</label>
    <select id="meals" name="meals" required="required">
      <option value="0">No Pre-Paid Meals</option>
      <option value="1">1 Pre-Paid Meals</option>
      <option value="2">2 Pre-Paid Meals</option>
      <option value="3">3 Pre-Paid Meals</option>
    </select>
  </div>

  <div>
    <label for="password">Password</label>
    <input type="password" id="password" name="password" />
    <p><?php echo $current == 'edit' ? "Leave blank to keep current password" : ""; ?></p>
  </div>

  <?php $is_same_user = $user->user_id == Auth::getInstance()->getCurrentUser()->user_id; ?>

  <div>
    <label for="isEnabled">
        <?php if ($is_same_user): ?>
            <input type="hidden" name="isEnabled" value="1" />
            <input type="checkbox" disabled="disabled" checked="checked" /> Enabled

        <?php else: ?>
            <input id="isEnabled" name="isEnabled" type="checkbox" value="1"
                <?php if ($user->isEnabled): ?>checked="checked"<?php endif; ?>/> Enabled
        <?php endif; ?>
    </label>
  </div>

  <div>
    <label for="isAdmin">
        <?php if ($is_same_user): ?>
            <input type="hidden" name="isAdmin" value="1" />
            <input type="checkbox" disabled="disabled" checked="checked" /> Administrator

        <?php else: ?>
            <input id="isAdmin" name="isAdmin" type="checkbox" value="1"
                <?php if ($user->is_admin): ?>checked="checked"<?php endif; ?>/> Administrator

        <?php endif; ?>
    </label>
  </div>

  <div>
    <label for="hasPermissions">
            <input id="hasPermissions" name="hasPermissions" type="checkbox" value="1"
                <?php if ($user->has_permissions): ?>checked="checked"<?php endif; ?>/> Enable Report Permissions
    </label>
  </div>

  <div>
    <label for="boardMember">
            <input id="boardMember" name="boardMember" type="checkbox" value="1"
                <?php if ($user->board_member): ?>checked="checked"<?php endif; ?>/> Board Member
    </label>
  </div>

  <div>
    <label for="isListed">
            <input id="isListed" name="isListed" type="checkbox" value="1"
                <?php if ($user->is_listed): ?>checked="checked"<?php endif; ?>/> Listed on Website
    </label>
  </div>

  <!--To be calculated as membership price + (meals * meal price) in AJAX/Js call-->
  <div>
    <label for="renewalAmount">Total Due</label>
    <input id="renewalAmount" name="renewalAmount" readonly="readonly"/>
  </div>

  <div>
    <label for="amountPaid">Amount Paid</label>
    <input id="amountPaid" name="amountPaid" value="<?php echo htmlspecialchars($user->amount_paid); ?>" />
  </div>

  <div>
    <label for="notes">Notes</label>
    <textarea id="notes" name="notes" value="<?php echo htmlspecialchars($user->notes); ?>"></textarea>
  </div>

  <input type="submit" value="Save" />
  <a href="/NSBA/admin/users<?php if (isset($user->user_id)) { echo '/show.php?user_id=' . $user->user_id; } ?>">Cancel</a>
</form>
<script src="../../includes/utility.js"></script>