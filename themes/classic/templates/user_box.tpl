<div id="useravatar"><img src="{{user.mainchar|avatar}}"></div>
<div class="username">{{ user.userdata.username }}</div>
<div class="chardata">
	{% if not user.mainchar.data.name %}
	keinen Haupt-Charachter ausgewählt
	{% else %}
	{{user.mainchar.data.name}} {{user.mainchar.data.level}} {{user.mainchar.realm.name}}
	{% endif %}
</div>
<div id="user_menu" style="display: none;">

<div class="verwaltungsbox">
	<div class="VerwaltungsBOX-01_">
		<img id="VerwaltungsBOX_01" src="themes/cata/images/box/VerwaltungsBOX_01.jpg" width="680" height="6" alt="" />
	</div>
	<div class="VerwaltungsBOX-02_">
		<img id="VerwaltungsBOX_02" src="themes/cata/images/box/VerwaltungsBOX_02.jpg" width="594" height="18" alt="" />
	</div>
	<div class="VerwaltungsBOX-03_">
		<a href="logout.php"><img id="VerwaltungsBOX_03" name="logout" src="themes/cata/images/box/VerwaltungsBOX_03.jpg" width="86" height="24" alt="" onmouseover="logout.src='themes/cata/images/box/VerwaltungsBOX_light_03.jpg'" onmouseout="logout.src='themes/cata/images/box/VerwaltungsBOX_03.jpg'" /></a>
	</div>
	<div class="VerwaltungsBOX-04_">
		<img id="VerwaltungsBOX_04" src="themes/cata/images/box/VerwaltungsBOX_04.jpg" width="298" height="266" alt="" />
	</div>
	<div class="VerwaltungsBOX-05_">
		<a href="user_characters.php"><img src="themes/cata/images/box/VerwaltungsBOX_05.jpg" name="link1" width="190" height="45" id="VerwaltungsBOX_05" onmouseover="link1.src='themes/cata/images/box/VerwaltungsBOX_light_05.jpg'" onmouseout="link1.src='themes/cata/images/box/VerwaltungsBOX_05.jpg'" /></a>
	</div>
	<div class="VerwaltungsBOX-06_">
		<img id="VerwaltungsBOX_06" src="themes/cata/images/box/VerwaltungsBOX_06.jpg" width="106" height="266" alt="" />
	</div>
	<div class="VerwaltungsBOX-07_">
		<img id="VerwaltungsBOX_07" src="themes/cata/images/box/VerwaltungsBOX_07.jpg" width="86" height="260" alt="" />
	</div>
	<div class="VerwaltungsBOX-08_">
		<a href="user_change_password.php" class="modalform" title="Passwort ändern" form-height="250" form-width="400"><img id="VerwaltungsBOX_08" name="link2" src="themes/cata/images/box/VerwaltungsBOX_08.jpg" width="190" height="55" alt="" onmouseover="link2.src='themes/cata/images/box/VerwaltungsBOX_light_08.jpg'" onmouseout="link2.src='themes/cata/images/box/VerwaltungsBOX_08.jpg'" /></a>
	</div>
	<div class="VerwaltungsBOX-09_">
		<a href="user_change_email.php" class="modalform" title="E-Mail ändern" form-height="250" form-width="400"><img id="VerwaltungsBOX_09" name="link3" src="themes/cata/images/box/VerwaltungsBOX_09.jpg" width="190" height="58" alt="" onmouseover="link3.src='themes/cata/images/box/VerwaltungsBOX_light_09.jpg'" onmouseout="link3.src='themes/cata/images/box/VerwaltungsBOX_09.jpg'" /></a>
	</div>
	<div class="VerwaltungsBOX-10_">
		<a href="tool_friends.php"><img id="VerwaltungsBOX_10" name="link4" src="themes/cata/images/box/VerwaltungsBOX_10.jpg" width="190" height="80" alt="" onmouseover="link4.src='themes/cata/images/box/VerwaltungsBOX_light_10.jpg'" onmouseout="link4.src='themes/cata/images/box/VerwaltungsBOX_10.jpg'" /></a>
	</div>
	<div class="VerwaltungsBOX-11_">
		<img id="VerwaltungsBOX_11" src="themes/cata/images/box/VerwaltungsBOX_11.jpg" width="190" height="28" alt="" />
	</div>
</div>

</div>
  <!-- USER DATA -->