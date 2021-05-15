
	<div class="holder_login">
		<div class="pasek_login"><img src="./img/zamek.png" alt="" style="float: left; margin-right: 20px;" /> Administrace <?php echo StringUtils::capitalize(__DOMENA__);?></div>
	
	<div class="formular">
			<form name="p" method="post" action="">	
			<table border="0" class="prihlaseni" valign="top" align="center">
				<tr>
				<td align="left" valign="top" colspan="2">
				Na vaši emailovou adresu byl zaslán PIN<br />
				PIN opište do políčka níže. Jeho platnost je 5 minut.<br />
				</td>
			
				</tr>
				<tr>
				<td align="left" valign="top">
				PIN:
				</td>
				<td>
				 <input type="text" name="pin" class="inp" maxlength="50" required />
				 </td>
				 </tr>
				 <tr>
				  <td>&nbsp;</td>
				  <td>
				<br />	 
				 <input type="submit" name="submit2" value="odeslat" />
				
				</td></tr>
				<tr><td align="left" valign="top" colspan="2" height="20"><br />&nbsp;
				<span class="r">{login_error}</span> 
			        </td>
		            </tr>
	               
			</table>
			</form>
		</div>
	</div>	
	


