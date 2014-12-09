<?php
include("next.tpl");
?>
<div id="build" class="gid15"><a href="#" onClick="return Popup(15,4);" class="build_logo">
	<img class="building g15" src="img/x.gif" alt="Main Building" title="Main Building" />
</a>
<h1>Main Building <span class="level">Level <?php echo $village->resarray['f'.$id]; ?></span></h1>
<p class="build_desc">村莊大樓(Main building)是你剛開始遊戲時的第一棟建築物。它就位於村莊的中心，這也是您村莊內總建築師的住所。中心大樓的級數愈高，其他建築物及資源的開發速度愈快。</p>


	<table cellpadding="1" cellspacing="1" id="build_value">
		<tr>
			<th>Current construction time:</th>
			<td><b><?php echo round($bid15[$village->resarray['f'.$id]]['attri']); ?></b> Percent</td>
		</tr>
		<tr>
		<?php
        if(!$building->isMax($village->resarray['f'.$id.'t'],$id)) {
		$next = $village->resarray['f'.$id]+1+$loopsame+$doublebuild+$master;
		if($next<=20){
        ?>
			<th>Construction time at level <?php echo $next; ?>:</th>
			<td><b><?php echo round($bid15[$next]['attri']); ?></b> Percent</td>
            <?php
            }else{
        ?>
			<th>Construction time at level 20:</th>
			<td><b><?php echo round($bid15[20]['attri']); ?></b> Percent</td>
            <?php
			}}
            ?>
		</tr>
	</table>

<?php
if($village->resarray['f'.$id] >= 10){
	include("Templates/Build/15_1.tpl");
}
include("upgrade.tpl");
?>
</p></div>
