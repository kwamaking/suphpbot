<?php
$function_map['channel'] = array(
	'join'=>'channel_join',
	'part'=>'channel_part',
	'op'=>'channel_mop',
	'deop'=>'channel_mop',
	'halfop'=>'channel_mop',
	'dehalfop'=>'channel_mop',
	'voice'=>'channel_mop',
	'devoice'=>'channel_mop',
	'protect'=>'channel_mop',
	'deprotect'=>'channel_mop',
	'owner'=>'channel_mop',
	'deowner'=>'channel_mop',
	'kick'=>'channel_kick'
);
function channel_join() {
	global $nick,$admin,$channel,$buffwords;
	if ($admin) {
		send('JOIN ' . $buffwords[4] . ' ' . $buffwords[5]);
	} else {
		send_msg($channel,$nick . ': You need to be an admin to tell ME to join channels!');
	}
}
function channel_part() {
	global $nick,$buffwords,$admin,$channel;
	if ($admin) {
		send('PART ' . $buffwords[4] . ' :' . $buffwords[5]);
	} else {
		send_msg($channel,$nick . ': Nice try, bro. I don\'t part channels unless you identify.');
	}
}
function channel_mop() {
	global $command,$channel,$args,$admin,$nick;
	$mops = array('op'=>'o','halfop'=>'h','voice'=>'v','protect'=>'a','owner'=>'q');
	if ($admin) {
		if ($args[0]=='') {
			$target = $nick;
		} else {
			$target = $args[0];
		}
		if (substr($command,0,2)=='de') {
			$prefix = '-';
			$command = substr(strtolower($command),2);
		} else {
			$prefix = '+';
			$command = strtolower($command);
		}
		if (isset($mops[$command])) {
			send('MODE ' . $channel . ' ' . $prefix . $mops[$command] . ' ' . $target);
		} else {
			send_msg($channel,'Something\'s gone horribly wrong.');
		}
	} else {
		send_msg($channel,'You lack permissions. Become an admin.');
	}
}
function channel_kick() {
	global $args,$channel,$nick,$admin;
	if (!$admin) {
		send_msg($channel,'You aren\'t going to be kicking anybody if you aren\'t an admin!');
	} elseif (count($args)==1&&$args[0]!=='') {
		send('KICK ' . $channel . ' ' . $args[0] . ' :Requested (' . $nick . ')');
	} elseif (count($args)>1) {
		send('KICK ' . $channel . ' ' . $args[0] . ' :' . $arguments . ' (' . $nick . ')');
	}
}
?>