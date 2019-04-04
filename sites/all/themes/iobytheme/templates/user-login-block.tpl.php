<label for="header-login-email">E-mail or username</label>
<input type="text" name="name" id="header-login-email" required/>
<label for="header-login-password">Password</label>
<input type="password" name="pass" id="header-login-password" required/>
<input class="button" type="submit" name="op" value="Log In"/>
<?php print substr($rendered, strpos($rendered, '<input type="hidden"')) ?>
<p><a href="/user/password">Or request a new password</a></p>
