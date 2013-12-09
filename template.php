<?php

// drupal_rebuild_theme_registry();

/* PREPROCESSING */

function uusm_preprocess(&$vars, $hook) {
	$vars['logged_in'] = FALSE;
	$vars['is_staff'] = FALSE;
	$vars['is_admin'] = FALSE;
	$vars['is_page'] = FALSE;
	$vars['is_story'] = FALSE;
	$vars['is_event'] = FALSE;
	$vars['is_term'] = FALSE;
	$vars['is_view'] = FALSE;
	$vars['is_node'] = FALSE;
	$vars['is_product'] = FALSE;
	$vars['is_gallery'] = FALSE;
	$vars['is_photo'] = FALSE;
	$vars['is_editing'] = FALSE;
	$vars['can_edit'] = FALSE;
	$vars['hd_img'] = '';
}

function uusm_preprocess_page(&$vars) {
	$vars['debugging'] = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', 0)) ? 1 : 0;
	$vars['pageid'] = '';
	$vars['mission'] = variable_get('site_mission', '');
	$vars['logged_in'] = ($vars['user']->uid > 0) ? TRUE : FALSE;
	$vars['is_admin'] = (in_array('webmaster', $vars['user']->roles) OR $vars['user']->uid == 1) ? TRUE : FALSE;
	$vars['is_staff'] = (in_array('office admin', $vars['user']->roles) OR in_array('office staff', $vars['user']->roles) OR $vars['is_admin']) ? TRUE : FALSE;
	$vars['is_view'] = (substr_count($vars['content'],"view-content")) ? TRUE : FALSE;
	$vars['is_term'] = (arg(0) == 'taxonomy' AND arg(1) == 'term') ? TRUE : FALSE;
	$vars['is_node'] = ($vars['node'] AND arg(1) != 'add' AND arg(2) != 'edit') ? TRUE : FALSE;
	if($vars['is_node']) {
		$vars['is_group'] = ($vars['is_node'] AND $vars['node']->type == 'group') ? TRUE : FALSE;
		$vars['is_page'] = ($vars['node']->type == 'page') ? TRUE : FALSE;
		$vars['is_event'] = ($vars['node']->type == 'event') ? TRUE : FALSE;
		$vars['is_story'] = ($vars['node']->type == 'story') ? TRUE : FALSE;
		$vars['is_policy'] = ($vars['node']->type == 'policy') ? TRUE : FALSE;
		$vars['is_product'] = ($vars['node']->type == 'product') ? TRUE : FALSE;
		$vars['is_photo'] = ($vars['node']->type == 'photo') ? TRUE : FALSE;
		$vars['is_sermon'] = ($vars['node']->type == 'sermon') ? TRUE : FALSE;
		$vars['is_gallery'] = ($vars['node']->type == 'photo' OR (arg(0) == 'gallery' AND arg(1))) ? TRUE : FALSE;
	}
	$vars['messages'] = html_entity_decode($vars['messages']);
	$vars['can_edit'] = ($vars['is_admin'] OR ($vars['is_node'] && ($vars['user']->uid == $vars['node']->uid)) OR (arg(0) == 'user' && $vars['user']->uid == arg(1))) ? TRUE : FALSE;
	$vars['is_editing'] = (arg(1) == 'add' OR arg(2) == 'edit' OR arg(2) == 'delete') ? TRUE : FALSE;
	if(!$vars['is_front']) {
		$vars['breadcrumb'] = uusm_generate_breadcrumb($_REQUEST['q']);
		if(!$vars['is_term'] AND !$vars['is_node']) {
			$vars['breadcrumb'] = uusm_generate_breadcrumb($_REQUEST['q']);
		}
	}
	if($vars['is_view']) {
		$vars['pagetype'] = ($vars['is_staff']) ? ' <span class="smallprint">[view]</span> ' : '';
	}
	if($vars['is_group']) {
		$vars['pageid'] = 'term-14';
	}
	if($vars['is_event']) {
		$toptid = 14;
		$vars['pageid'] = 'term-' . $toptid;
		$vars['hd_img'] = taxonomy_image_display($toptid);
	}
	if($vars['is_policy']) {
		$toptid = 12;
		$vars['pageid'] = 'term-' . $toptid;
		$vars['hd_img'] = taxonomy_image_display($toptid);
	}
	if($vars['is_sermon']) {
		$url_string = explode('/',$vars['node']->path);
		$toptid = 13;
		$vars['pageid'] = 'term-' . $toptid;
		$vars['hd_img'] = taxonomy_image_display($toptid);
	}
	if($vars['is_page']) {
		$vars['head_title'] = strip_tags($vars['node']->title);
		if(count($vars['node']->taxonomy)) {
			foreach($vars['node']->taxonomy AS $key => $thing) {
				 if(!$vars['pageid']) { $vars['pageid'] = 'term-' . $key; }
				$taxodata = taxonomy_get_parents_all($key);
				// DEPARTMENT VOCAB == NAVIGATION VOCAB
				if($taxodata[0]->vid == 5) {
					$navidata = taxonomy_get_parents_all($key);
				}
			}
		}
		if(count($navidata)) {
			foreach($navidata AS $thing) {
				// if(!$vars['pageid']) {
					$vars['pageid'] = 'term-' . $thing->tid;
				// }
			}
		} else {
			$vars['pageid'] = 'uncategorized';
		}
		if($vars['node']->field_hd_img[0]['fid']) {
			$vars['hd_img'] = theme('imagecache','page_wide',$vars['node']->field_hd_img[0]['filepath']);
		} else {
			$vars['hd_img'] = taxonomy_image_display($thing->tid);
		}
	}
	if($vars['is_product']) {
		$vars['head_title'] = strip_tags($vars['node']->title);
	}
	if($vars['is_term']) {
		$thetid = arg(2);
		$taxodata = taxonomy_get_parents_all($thetid);
		if(count($taxodata) > 1) {
			foreach($taxodata AS $thing) {
				$vars['pageid'] = 'term-' . $thing->tid;
			}
		} elseif(count($taxodata)) {
			foreach($taxodata AS $thing) {
				if(!$vars['pageid']) { $vars['pageid'] = 'term-' . $thing->tid; }
			}
		} else {
			$vars['pageid'] = 'term-' . $thetid;
		}
		$vars['hd_img'] = taxonomy_image_display($thetid);
		if(user_access('administer taxonomy')) {
			$vars['tabs'] = '<ul class="tabs primary">
<li class="active" ><a href="/taxonomy/term/' . arg(2) . '" class="active">View</a></li>
<li><a href="/admin/content/taxonomy/edit/term/' . arg(2) . '?destination=taxonomy/term/' . arg(2) . '">Edit</a></li>
</ul>';
		}
		$vars['pagetype'] = ($vars['is_staff']) ? ' <span class="smallprint">[term]</span> ' : '';
		// SOME TERMS ARE SPECIAL...
		if($thetid == 90 OR $thetid == 99 OR $thetid == 97 OR $thetid == 98) {
			$vars['show_list'] = TRUE;
			// $vars['pageid'] = 'term-14';
		}
		if($thetid == 15 AND !$vars['user']->uid) {
			$vars['anon_message'] = "<div class='node'><p>You must be logged in to view this section.</p></div>";
		}
	}
	
	$url_string = explode('/',$vars['node']->path);
	
	switch($url_string[0]) {
		case 'for-visitors' :
			$toptid = 11;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'about-our-church' :
			$toptid = 12;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'sundays-services' :
			$toptid = 13;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'sundays-and-services' :
			$toptid = 13;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'worship-and-sermons' :
			$toptid = 13;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'getting-involved' :
			$toptid = 14;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'for-members' :
			$toptid = 15;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'calendar' :
			$toptid = 14;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'aggregator' :
			$toptid = 11;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'comment' :
			$vars['title'] = 'Post Your Comment'; $vars['head_title'] = 'Post Comment';
			break;
		case 'contact' :
			$toptid = 12;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			$vars['breadcrumb'] = uusm_generate_breadcrumb('about-our-church/staff-directory/contact-us');
			$vars['is_page'] = TRUE;
			break;
	}
	
	
	// PATH-BASED VARS
	switch(arg(0)) {
		case 'for-visitors' :
			$toptid = 11;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'about-our-church' :
			$toptid = 12;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'sundays-services' :
			$toptid = 13;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'sundays-and-services' :
			$toptid = 13;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'worship-and-sermons' :
			$toptid = 13;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'getting-involved' :
			$toptid = 14;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'for-members' :
			$toptid = 15;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'calendar' :
			$toptid = 14;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'aggregator' :
			$toptid = 11;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			break;
		case 'comment' :
			$vars['title'] = 'Post Your Comment'; $vars['head_title'] = 'Post Comment';
			break;
		case 'contact' :
			$toptid = 12;
			$vars['pageid'] = 'term-' . $toptid;
			$vars['hd_img'] = taxonomy_image_display($toptid);
			$vars['breadcrumb'] = uusm_generate_breadcrumb('about-our-church/staff-directory/contact-us');
			$vars['is_page'] = TRUE;
			break;
		 
		case 'newsletters' :
			$vars['pageid'] = 'term-12';
			break;
			
		case 'node' :
			// $vars['breadcrumb'] = '&nbsp;';
			 //$vars['is_page'] = TRUE;
			// $vars['pagetype'] = ($vars['is_staff'] AND $vars['is_node']) ? ' [' . $vars['node']->type . '] ' : '';
			$vars['editlink'] = ($vars['is_staff'] AND $vars['is_node']) ? " [<a class='white' href='/node/" . $vars['node']->nid . "/edit'>EDIT</a>]" : '';
			if($vars['debugging']) {
				if(!$vars['secondary_links']) {
					$chunks = explode('/',$vars['node']->path);
					if($chunks[0] != 'node') {
						$chunk1 = uusm_humanify($chunks[0]);
						$chunk2 = $chunks[1];
						$mlid = uusm_get_mlid($chunk1);
						$toptid = uusm_get_tid_from_link_path($mlid);					
						$vars['pageid'] = 'term-' . $toptid;
						$secondary_menu = uusm_get_secondary_menu($mlid, $chunk2);
						$vars['secondary_links'] = $secondary_menu;
					}
				}
			}
			break;
		case 'sitemap' :
			$vars['breadcrumb'] = '&nbsp;';
			$vars['is_page'] = TRUE;
			break;
		case 'user' :
			// $vars['breadcrumb'] = '&nbsp;';
			$vars['pageid'] = 'term-15';
			$vars['pagetype'] = ($vars['is_staff']) ? ' <span class="smallprint">[user]</span> ' : '';
			if(arg(1) AND arg(1) != 'login' AND arg(1) != 'register' AND arg(1) != 'password') {
				if($vars['can_edit']) {
					$vars['tabs'] = '<ul class="tabs primary clear-block">';
					$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '"><span class="tab">View</span></a></li>';
					$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/edit"><span class="tab">Edit Account</span></a></li>';
					$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/profile/profile"><span class="tab">Edit Profile</span></a></li>';
					$vars['tabs'] .= '<li ><a href="/og/my"><span class="tab">My Groups</span></a></li>';
					$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/invites"><span class="tab">Invitations</span></a></li>';
					$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/contact"><span class="tab">Contact ' . $vars['user']->name . '</span></a></li>';
					// $vars['tabs'] .= '<li ><a href="/user/' . $vars['node']->uid . '/track/navigation"><span class="tab">Track page visits</span></a></li>';
					$vars['tabs'] .= '</ul>';
					if(arg(2) == 'invites') {
						$vars['suppress_title'] = TRUE;
						$vars['is_profile'] = TRUE;
						$vars['tabs'] = '<ul class="tabs primary clear-block">';
						$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '"><span class="tab">View</span></a></li>';
						$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/edit"><span class="tab">Edit Account</span></a></li>';
						$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/profile/profile"><span class="tab">Edit Profile</span></a></li>';
						$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/community"><span class="tab">Community</span></a></li>';
						$vars['tabs'] .= '<li ><a href="/og/my"><span class="tab">Groups</span></a></li>';
						$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/invites" class="active"><span class="tab">Invitations</span></a></li>';
						$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/contact"><span class="tab">Contact</span></a></li>';
						// $vars['tabs'] .= '<li ><a href="/user/' . $vars['node']->uid . '/track/navigation"><span class="tab">Track page visits</span></a></li>';
						$vars['tabs'] .= '</ul>';
						$vars['tabs'] .= '<ul class="tabs secondary clear-block">';
						$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/invites"><span class="tab">Accepted</span></a></li>';
						$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/invites/pending"><span class="tab">Pending</span></a></li>';
						$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/invites/expired"><span class="tab">Expired</span></a></li>';
						$vars['tabs'] .= '<li ><a href="/user/' . $vars['user']->uid . '/invites/new"><span class="tab">New Invitation</span></a></li>';
						$vars['tabs'] .= '</ul>';
					}
				} else {
					if($vars['logged_in']) {
						$vars['tabs'] = '<ul class="tabs primary clear-block">';
						$vars['tabs'] .= '<li ><a href="/user/' . arg(1) . '/contact"><span class="tab">Contact ' . $vars['title'] . '</span></a></li>';
						// $vars['tabs'] .= '<li ><a href="/user/' . $vars['node']->uid . '/track/navigation"><span class="tab">Track page visits</span></a></li>';
						$vars['tabs'] .= '</ul>';
					}
				}
			}
			if(arg(1) == 'register') {
				// $vars['canonical'] = 'http://www.uuccsm.org/user/register';
				$vars['head_title'] = 'Create an Account';
			} elseif(arg(1) == 'login') {
				// login page in shadowbox
				$vars['pageid'] = 'login';
				$vars['head_title'] = 'Login to your UUCCSM Account';
				if($user->uid) {
					$vars['title'] = 'Member Login';
				} else {
					$vars['title'] = 'Please Sign In';
				}
				// $vars['content'] = "<div id='pretty_login_form'>" . $vars['content'] . '</div>';
			} elseif(arg(1) == 'password') {
				// registration page in shadowbox
				$vars['head_title'] = 'Request New Password';
				$vars['title'] = 'Request New Password';
			} elseif(arg(1) AND !arg(2)) {
				// profile
			} elseif(!arg(1) AND !$vars['logged_in']) {
				// login page
				$vars['pageid'] = 'login';
				$vars['head_title'] = 'Login to your UUCCSM Account';
				$vars['title'] = 'Member Login';
				// $vars['content'] = "<div id='pretty_login_form'>" . $vars['content'] . '</div>';
				$vars['anon_message'] = '<p>You must be logged in to view this section.</p>';
				$vars['content_bottom'] = '';
			} elseif(arg(3)) {
				// about me
				$vars['pageid'] = 'about_me';
			}
			break;
		default	:
			// nothing
	}
	switch($vars['pageid']) {
		case 'term-11' :
			$vars['suckermenu'] = 1;
			break;
		case 'term-12' :
			$vars['suckermenu'] = 2;
			break;
		case 'term-13' :
			$vars['suckermenu'] = 3;
			break;
		case 'term-14' :
			$vars['suckermenu'] = 4;
			break;
		case 'term-15' :
			$vars['suckermenu'] = 5;
			$vars['secondary_links'] = array(
				'menu-login' => array(
					attributes => array('rel' => 'shadowbox;width=544;height=250'),
					href => 'user/login',
					title => 'LOG IN',
				),
			);
			break;
	}
	if($vars['pageid'] == 'term-15' AND arg(0) != 'user' AND !$vars['user']->uid) {
		$vars['content'] = '<p>You must be logged in to view this section.</p>';
		$vars['content_bottom'] = '';
	}		
	if($vars['debugging']) {
		$debugstr .= "<div><textarea class=debug cols=80 rows=20 style='margin-left:25px;font-size: 10px; background-color:#fee'>page vars = ";
			$debugstr .= arg(0) . '/' . arg(1) . '/' . arg(2) . "\n";
			$debugstr .= $vars['node']->path . "\n";
			$debugstr .= htmlentities(print_r($vars,1));
		$debugstr .= '</textarea></div>';
		$vars['debug_dump'] = '<br><br>' . $debugstr;
	}
}

function uusm_preprocess_node(&$vars) {
	$vars['debugging'] = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', 0)) ? 1 : 0;
	$vars['is_admin'] = (in_array('webmaster', $vars['user']->roles) OR $vars['user']->uid == 1) ? TRUE : FALSE;
	$vars['is_staff'] = (in_array('office admin', $vars['user']->roles) OR in_array('office staff', $vars['user']->roles) OR $vars['is_admin']) ? TRUE : FALSE;
	$vars['is_editing'] = (arg(1) == 'add' OR arg(2) == 'edit' OR arg(2) == 'delete') ? TRUE : FALSE;
	$vars['is_product'] = ($vars['node']->type == 'product') ? TRUE : FALSE;
	$vars['is_author'] = ($vars['user']->uid == $vars['node']->uid) ? TRUE : FALSE;
	$vars['comment_count'] = $vars['node']->comment_count;
	$vars['pagetype'] = ($vars['is_staff'] AND arg(1) != 'add' AND arg(2) != 'edit') ? ' <span class="smallprint">[' . $vars['type'] . ']</span> ' : '';
	$vars['editlink'] = (node_access('edit', arg(1))) ? " [<a class='editlink' href='/node/" . arg(1) . "?destination=http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . "'>EDIT</a>]" : '';
	if($vars['debugging']) {
		$debugstr .= "<textarea class=debug cols=80 rows=20 style='margin-left:50px;font-size: 10px; background-color:#ffe'>node vars = \n";
			$debugstr .= htmlentities(print_r($vars,1));
		$debugstr .= '</textarea>';
		$vars['debug_dump'] = '<br><br>' . $debugstr;
	}
}

function uusm_generate_breadcrumb($path) {
	$chunks = explode('/',$path);
	$retstr = "<div class='breadcrumb'>You are here: <a href='/'>Home</a>";
	$linkstr = '';
	foreach($chunks AS $thing) {
		$linkstr .= "/" . $thing;
		$textstr = uusm_humanify($thing);
		$retstr .= " &raquo; <a href='" . $linkstr . "'>" . $textstr . "</a>";
	}
	$retstr .= '</div>';
	return $retstr;
}

function uusm_get_secondary_menu($plid, $curr) {
	$sql = "SELECT mlid, link_path, link_title FROM menu_links WHERE plid = %d ORDER BY weight, link_title";
	$result = db_query($sql, $plid);
	$menu = array();
	while ($r = db_fetch_object($result)) {
		$mlid = $r->mlid;
		$href = $r->link_path;
		$title = $r->link_title;
		$itemname = 'menu-' . $mlid;
		$menu[$itemname] = array(
			'href' => $href,
			'title' => $title,
		);
		if(uusm_clean_filename($title) == $curr) {
			$menu[$itemname]['attributes'] = array('class' => 'active');
		}
	}
	return $menu;
}

function uusm_get_mlid($str) {
	$sql = "SELECT mlid FROM menu_links WHERE link_title LIKE '" . $str . "'";
	$result = db_query($sql, $str);
	while ($r = db_fetch_object($result)) {
		$mlid = $r->mlid;
	}
	return $mlid;
}

function uusm_get_tid_from_link_path($val) {
	$sql = "SELECT link_path FROM menu_links WHERE mlid = %d";
	$result = db_query($sql, $val);
	while ($r = db_fetch_object($result)) {
		$link_path = $r->link_path;
	}
	$chunks = explode('/',$link_path);
	$tid = $chunks[2];
	return $tid;
}

function uusm_get_video($nid) {
	$sql = "SELECT v.nid FROM content_type_video v LEFT JOIN relativity r ON r.nid = v.nid WHERE r.parent_nid = %d";
	$result = db_query($sql, $nid);
	while ($r = db_fetch_object($result)) {
		$childnids[] = $r->nid;
	}
	return $childnids;
}

/* 
function uusm_preprocess_block(&$vars) {
	$vars['debugging'] = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', 0)) ? 1 : 0;
}
*/

function uusm_preprocess_user_profile(&$vars) {
	$vars['is_admin'] = (in_array('webmaster', $vars['user']->roles) OR $vars['user']->uid == 1) ? TRUE : FALSE;
	$vars['is_staff'] = (in_array('office admin', $vars['user']->roles) OR in_array('office staff', $vars['user']->roles) OR $vars['is_admin']) ? TRUE : FALSE;
	$vars['editlink'] = ($vars['is_staff'] OR $vars['node']->uid = $vars['account']->uid) ? " [<a class='white' href='/user/" . $vars['user']->nid . "/edit'>EDIT</a>]" : '';
}

function uusm_links(&$links, $attributes = array('class' => 'links')) {
	$debugging = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', '')) ? TRUE : FALSE;
	if (!count($links)) {
		return '';
	}
	$cells = count($links) - 1;
	if($attributes['class'] == 'links primary-links') {
		$output = "<table id='primary-links'><tr>\n";
		foreach ($links as $key => $link) {
		$class = $key;
		$theurl = ($remote) ? $link['href'] : drupal_get_path_alias($link['href']);
			if($link['href'] == 'frontpage') {
				$w = 50;
				$output .= '<td id="home" width="' . $w . '"';
				$output .= ' OnMouseOver=\'roll("home", "lit");return true;\' OnMouseOut=\'roll("home", "dim");return true;\'';
				$output .= '>';
				$output .= "<a style='width:" . $w . "px;' href='/'>";
				$output .= "<img src='/sites/default/files/shim.gif' width='50' height='30' border=0></a></td>\n";
			} else {
				/* 
				if($debugging) {
					$msg = "<textarea cols=80 rows=10 style='font-size: 10px;'>";
					// $msg .= 'attrs = ' . htmlentities(print_r($attributes,1)) . "\n";
					$msg .= 'key = ' . htmlentities(print_r($key,1));
					$msg .= '</textarea>';
					// echo $msg;
					drupal_set_message($msg);
				}
				*/
				$w = floor(916 / ($cells));
				$theone = (stristr($key, 'active')) ? TRUE : FALSE;
				$remote = (stristr($link['href'], 'http')) ? TRUE : FALSE;
				$shortname = uusm_clean_filename($link['title']);
				$picname = 'nav_' . $shortname . '.png';
				$vertical_offset = (substr_count($class,"active")) ? '-60px' : 'top';
				if($key == 'menu-2119' AND substr_count($_REQUEST['q'],"about-our-church/governance/policies")) {
					$vertical_offset = '-60px';
				}
				switch($shortname) {
					case 'for-visitors' :
						$bgcolor = '#b3c579';
						break;
					case 'about-our-church' :
						$bgcolor = '#81bab1';
						break;
					case 'sundays-services' :
						$bgcolor = '#efd38a';
						break;
					case 'sundays-and-services' :
						$bgcolor = '#efd38a';
						break;
					case 'getting-involved' :
						$bgcolor = '#86add6';
						break;
					case 'for-members' :
						$bgcolor = '#e5a671';
						break;
					default	:
						$bgcolor = '#d9ceb2';
				}
				// $output .= '<td id="' . $theurl . '" width="' . $w . '"';
				$output .= '<td name="' . $theurl . '" id="' . $theurl . '"';
				$output .= " style='width:" . $w . "px;background:" . $bgcolor . " url(/sites/all/themes/uusm/images/" . $picname . ") center " . $vertical_offset . " no-repeat;'";
				if ($theone) { $output .= ' class="active"'; }
				// $output .= '><div style="width:auto;"';
				if(!$theone) {
					$output .= ' OnMouseOver=\'roll("' . $shortname . '", "lit");return true;\' OnMouseOut=\'roll("' . $shortname . '", "dim");return true;\'';
				}
				$output .= '>';
				$output .= '<a style="width:' . $w . 'px;" href="';
				if(!$remote) {
					$output .= '/';
				}
				$output .= $theurl . '"';
				if($remote) {
					$output .= ' target="_blank"';
				}
				// $output .= '>' . "<img src='/sites/default/files/shim.gif' width='" . ($w-2) . "' height='30' border=0>" . "</a>" ."</div></td>\n";
				$output .= '>' . "<img src='/sites/default/files/shim.gif' width='" . ($w-2) . "' height='30' border=0>" . "</a>" ."</td>\n";
			}
		}
		$output .= '</tr></table>';
	} elseif($attributes['class'] == 'links secondary-links') {
		$output .= '<ul class="links secondary-links">';
		foreach($links AS $key => $thing) {
			$slash = (substr_count($thing['href'],"http")) ? '' : '/';
			$target = ($slash) ? '' : ' target=_blank';
			$output .= '<li class="' . $key . '">';
			$output .= '<a rel="shadowbox;width=544;height=250" ' . $target . ' href="' . $slash . $thing['href'] . '" title="">' . $thing['title'] . '</a></li>';
			// $link = '<a rel="shadowbox;width=544;height=250" href="/user/login" title="">LOG IN</a>';
		}
		$output .= '</ul>';
	} else {
		if (count($links) > 0) {
			$output = '<ul' . drupal_attributes($attributes) . '>';
			$num_links = count($links);
			$i = 1;
			foreach ($links as $key => $link) {
				$class = $key;
				// Add first, last and active classes to the list of links to help out themers.
				if ($i == 1) {
					$class .= ' first';
				}
				if ($i == $num_links) {
					$class .= ' last';
				}
				if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
						 && (empty($link['language']) || $link['language']->language == $language->language)) {
					$class .= ' active';
				}
				$output .= '<li' . drupal_attributes(array('class' => $class)) . '>';
				if (isset($link['href'])) {
					// Pass in $link as $options, they share the same keys.
					$output .= l($link['title'], $link['href'], $link);
				}
				else if (!empty($link['title'])) {
					// Some links are actually not links, but we wrap these in <span> for adding title and class attributes
					if (empty($link['html'])) {
						$link['title'] = check_plain($link['title']);
					}
					$span_attributes = '';
					if (isset($link['attributes'])) {
						$span_attributes = drupal_attributes($link['attributes']);
					}
					$output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
				}
				$i++;
				$output .= "</li>\n";
			}
			$output .= '</ul>';
		}
	}
	return $output;
}

/* THEME OVERRIDES */

function uusm_theme() {
	$hooks['user_login_form'] = array(
		'template' => 'user-login',
		'arguments' => array('form' => NULL),
	);
	array(
    'calendar_ical_icon' => array(
      'arguments' => array('url'),
      ),
    );
	/*return array(
    	'search_block_form' => array(
      	'arguments' => array('form' => NULL),
    	),
  	);*/
	return $hooks;
}

// iCal logo change

function uusm_calendar_ical_icon($url) {
  if ($image = theme('image', drupal_get_path('module', 'date_api') .'/images/ical16x16.gif', t('Add to calendar'), t('Add to calendar'))) {
	  $image = theme('image', drupal_get_path('theme', 'uusm') .'/images/add_to_calendar.gif', t('Add to calendar'), t('Add to calendar'));
    return '<a href="'. check_url($url) .'" class="ical-icon" title="ical">Add to your calendar '. $image .'</a>';
  }
}

// user_login.tpl.php
function uusm_preprocess_user_login_form(&$variables) {
	$variables['rendered'] = drupal_render($variables['form']);
	$variables['template_files'] = '';
	$variables['form']['name']['#description'] = '';
	$variables['form']['pass']['#description'] = '';
	//$variables['form']['submit']['#value'] = '';
	//$variables['form']['#attributes'] = array('target' => '_parent');
	//$variables['remember_me'] = '<div class="floaty remember_me" id="forgot"><p><a href="/user/password" target=_parent title="Request new password via e-mail.">I forgot my password.</a></p><p>Don\'t have an account yet? <a target=_parent href="/user/register">Register here.</a></p></div>';
	// $variables['rendered'] .= '<hr>' . drupal_get_form('user_register');
	/* 
	$debugging = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', '')) ? TRUE : FALSE;
	if($debugging) {
		$msg = "<textarea cols=80 rows=50 style='font-size: 10px;'>";
		$msg .= 'vars = ' . htmlentities(print_r($variables,1)) . "\n";
		$msg .= '</textarea>';
		echo $msg;
		// drupal_set_message($msg);
	}
	*/
}

/**
 * Helper function that builds the nested lists of a Nice menu.
 *
 * @param $menu
 *	 Menu array from which to build the nested lists.
 * @param $depth
 *	 The number of children levels to display. Use -1 to display all children
 *	 and use 0 to display no children.
 * @param $trail
 *	 An array of parent menu items.
 */
function uusm_nice_menus_build($menu, $depth = -1, $trail = NULL) {
	/* 
	$debugging = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', '')) ? TRUE : FALSE;
	if($debugging) {
		$msg = "<textarea cols=80 rows=20 style='font-size: 10px;'>";
		$msg .= 'vars = ' . htmlentities(print_r($menu,1)) . "\n";
		$msg .= '</textarea>';
		echo $msg;
		// drupal_set_message($msg);
	}
	*/
	$output = '';
	// Prepare to count the links so we can mark first, last, odd and even.
	$index = 0;
	$count = 0;
	foreach ($menu as $menu_count) {
		if ($menu_count['link']['hidden'] == 0) {
			$count++;
		}
	}
	// Get to building the menu.
	foreach ($menu as $menu_item) {
		$mlid = $menu_item['link']['mlid'];
		// Check to see if it is a visible menu item.
		if (!isset($menu_item['link']['hidden']) || $menu_item['link']['hidden'] == 0) {
			// Check our count and build first, last, odd/even classes.
			$index++;
			$first_class = $index == 1 ? ' first ' : '';
			$oddeven_class = $index % 2 == 0 ? ' even ' : ' odd ';
			$last_class = $index == $count ? ' last ' : '';
			// Build class name based on menu path
			// e.g. to give each menu item individual style.
			// Strip funny symbols.
			$clean_path = str_replace(array('http://', 'www', '<', '>', '&', '=', '?', ':', '.'), '', $menu_item['link']['href']);
			// Convert slashes to dashes.
			$clean_path = str_replace('/', '-', $clean_path);
			$class = 'menu-path-'. $clean_path;
			if ($trail && in_array($mlid, $trail)) {
				$class .= ' active-trail';
			}
			if ($mlid == 2133 AND substr_count($_REQUEST['q'],"about-our-church/governance/policies")) {
				$class .= ' active-trail';
			}
			// If it has children build a nice little tree under it.
			if ((!empty($menu_item['link']['has_children'])) && (!empty($menu_item['below'])) && $depth != 0) {
				// Keep passing children into the function 'til we get them all.
				$children = theme('nice_menus_build', $menu_item['below'], $depth, $trail);
				// Set the class to parent only of children are displayed.
				$parent_class = ($children && ($menu_item['link']['depth'] <= $depth || $depth == -1)) ? 'menuparent ' : '';
				$output .= '<li class="menu-' . $mlid . ' ' . $parent_class . $class . $first_class . $oddeven_class . $last_class .'">'. theme('menu_item_link', $menu_item['link']);
				// Check our depth parameters.
				if ($menu_item['link']['depth'] <= $depth || $depth == -1) {
					// Build the child UL only if children are displayed for the user.
					if ($children) {
						$output .= '<ul>';
						$output .= $children;
						$output .= "</ul>\n";
					}
				}
				$output .= "</li>\n";
			}
			else {
				$output .= '<li class="menu-' . $mlid . ' ' . $class . $first_class . $oddeven_class . $last_class .'">'. theme('menu_item_link', $menu_item['link']) .'</li>'."\n";
			}
		}
	}
	return $output;
}

function uusm_menu_item($link, $has_children, $menu = '', $in_active_trail = FALSE, $extra_class = NULL) {
	global $user;
	$class = ($menu ? 'expanded' : ($has_children ? 'collapsed' : 'leaf'));
	if (!empty($extra_class)) {
		$class .= ' '. $extra_class;
	}
	if ($in_active_trail) {
		$class .= ' active-trail';
	}
	if($link == '<a href="/node" title="">MAKE A DONATION</a>') {
		$class .= ' bluelink';
	}			 
	/* 
	if($link == '<a href="/for-members/my-account" title="">My Account</a>' AND !$user->uid) {
		$link = '';
	}
	if($link == '<a href="/for-members/my-calendar" title="">My Calendar</a>' AND !$user->uid) {
		$link = '';
	}
	if($link == '<a href="/for-members/church-directory" title="">Church Directory</a>' AND !$user->uid) {
		$link = '';
	}
	
	if($link == '<a href="/user/login" title="">Log In</a>') {
		$class .= ' bluelink';
		$link = '<a rel="shadowbox;width=544;height=250" href="/user/login" title="">LOG IN</a>';
	}
	if($link == '<A title="" href="/user/login" jQuery1316308273703="119">LOG IN</A>') {
		$link = '<a rel="shadowbox;width=544;height=250" href="/user/login" title="">LOG IN</a>';
	}*/
	/* 
	$debugging = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', '')) ? TRUE : FALSE;
	if($debugging) {
		$msg = "<textarea cols=80 rows=5 style='font-size: 10px;'>";
		$msg .= 'link = ' . htmlentities(print_r($link,1)) . "\n";
		// $msg .= 'links = ' . htmlentities(print_r($links,1));
		$msg .= '</textarea>';
		// echo $msg;
		drupal_set_message($msg);
	}
	*/
	if($link) { return '<li class="'. $class .'">'. $link . $menu ."</li>\n"; }
}

function uusm_menu_creation_by_array($tree, $trail) {
	$menu_output = recursive_link_creator($tree, $trail);
	if (!empty($menu_output)) {
	//We create the shell to hold the menu outside the recursive function.
	foreach($tree AS $key => $thing) {
		if(substr_count($key,"VISITORS")) {
			$thisbutton = 'green';
		}
		if(substr_count($key,"ABOUT")) {
			$thisbutton = 'teal';
		}
		if(substr_count($key,"SUNDAYS")) {
			$thisbutton = 'gold';
		}
		if(substr_count($key,"GETTING")) {
			$thisbutton = 'blue';
		}
		if(substr_count($key,"MEMBERS")) {
			$thisbutton = 'orange';
		}
	}
	$output	= '<!--[if IE]><div class="ie"><![endif]-->';
	// $output .= '<ul class="menu jquerymenu">';
	$output .= '<ul class="menu jquerymenu" OnMouseOver="buttonroll(\'' . $thisbutton . '\', \'lit\');" OnMouseOut="buttonroll(\'' . $thisbutton . '\', \'dim\');">';
	$output .= $menu_output;
	$output .= '</ul>';
	$output .= '<!--[if IE]></div><![endif]-->';
	/* 
	$debugging = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', '')) ? TRUE : FALSE;
		if($debugging) {
		$msg = "<textarea cols=80 rows=10 style='font-size: 10px;'>";
		$msg .= htmlentities(print_r($tree,1));
		$msg .= '</textarea>';
		// echo $msg;
		drupal_set_message($msg);
	}
	*/
	return $output;
	}
}

function uusm_jqmenu_links($title, $path, $options, $state, $classes, $has_children, $editpath = NULL, $edit_text = NULL, $edit_access) {
	$debugging = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', '')) ? TRUE : FALSE;
	global $base_path;
	$module_path = $base_path . drupal_get_path('module', 'jquerymenu');
	$output = '';
	if(count($classes)) {
		$classes[] = 'magical';
		if(substr_count($title,"VISITORS")) {
			$thisbutton = 'green';
		}
		if(substr_count($title,"ABOUT")) {
			$thisbutton = 'teal';
		}
		if(substr_count($title,"SUNDAYS")) {
			$thisbutton = 'gold';
		}
		if(substr_count($title,"GETTING")) {
			$thisbutton = 'blue';
		}
		if(substr_count($title,"MEMBERS")) {
			$thisbutton = 'orange';
		}
		/* 
		if($debugging) {
			$msg = "<textarea cols=70 rows=6 style='font-size: 10px;'>";
			$msg .= htmlentities(print_r($title,1));
			$msg .= '</textarea>';
			// echo $msg;
			drupal_set_message($msg);
		}
		*/
	}
	if($title == 'LOG IN') {
		$options['attributes']['rel'] = 'shadowbox;width=550;height=280';
	}
	// This is the span that becomes the little plus and minus symbol.
	$plus = '<span'. (empty($classes) ? '>' : ' id="' . $thisbutton . '" class="'. implode(' ', $classes) .'">') .'</span>';
	// SPAN DOESNT HEAR THE ROLLOVER
	// $plus = '<span'. (empty($classes) ? '>' : ' id="' . $thisbutton . '" class="'. implode(' ', $classes) .'" OnMouseOver="buttonroll(\'' . $thisbutton . '\', \'lit\');" OnMouseOut="buttonroll(\'' . $thisbutton . '\', \'dim\');">') .'</span>';
	if ($editpath != NULL && user_access($edit_access)) {
		$editbox = jqm_edit_box($editpath, $edit_text);
		if ($has_children != 0) {
			$output .= $editbox	. $plus . l($title, $path, $options);
		}
		else {
			$output .= $editbox	. l($title, $path, $options);
		}
	}
	else {
		if ($has_children != 0) {
			$output .= $plus . l($title, $path, $options);
			// ANCHOR DOESNT HEAR THE ROLLOVER
			// $output .= $plus . '<a OnMouseOver="buttonroll(\'' . $thisbutton . '\', \'lit\');" OnMouseOut="buttonroll(\'' . $thisbutton . '\', \'dim\');" href="/' . $path . '">' . $title . '</a>';
		}
		else {
			$output .= l($title, $path, $options);
		}
	}
	/* 
	if($thisbutton) {
		if($debugging) {
			$msg = "<textarea cols=70 rows=6 style='font-size: 10px;'>";
			$msg .= htmlentities(print_r($output,1));
			$msg .= '</textarea>';
			// echo $msg;
			drupal_set_message($msg);
		}
	}
	*/
	return $output;
}

/**
 * Theme the way an 'all day' label will look.
 */
function uusm_date_all_day_label() {
	return date_t('', 'datetime');
}

function uusm_text_resize_block() {
	if (_get_text_resize_reset_button() == TRUE) {
		$output = t('<a href="#" id="text_resize_size"><sup>Size:</sup>A</a> <a href="javascript:;" class="changer" id="text_resize_increase"><sup>+</sup>A</a> <a href="javascript:;" class="changer" id="text_resize_decrease"><sup>-</sup>A</a> <a href="javascript:;" class="changer" id="text_resize_reset">A</a><div id="text_resize_clear"></div>');
	}
	else {
		$output = t('<a href="#" id="text_resize_size"><sup>Size:</sup>A</a> <img src="/sites/all/themes/uusm/images/icon_text_size.gif" border=0><a href="javascript:;" class="changer" id="text_resize_decrease"><sup>-</sup>A</a> <a href="javascript:;" class="changer" id="text_resize_increase"><sup>+</sup>A</a><div id="text_resize_clear"></div>');
	}
	return $output;
}

function uusm_get_parent_videos($nid) {
	$codearray = array();
	$result = db_query('SELECT r.parent_nid as pid, v.field_yt_embed_value AS code FROM {relativity} r LEFT JOIN content_type_video v ON v.nid = r.parent_nid WHERE r.nid = %d', $nid);
	while ($video = db_fetch_object($result)) {
		if($video->code) { $codearray[] = $video->code; }
	}
	return $codearray;
}

function uusm_relativity_show_children($parent, $fieldset=1) {
	$output = '';
	// load all nodes associated with this one as the parent nid and show links to all of them.
	$result = db_query('SELECT nid FROM {relativity} WHERE parent_nid = %d', $parent->nid);
	while ($child = db_fetch_object($result)) {
		$child_nodes[] = node_load($child->nid);
	}
	if (!is_array($child_nodes)) return '';
	$childrentypes = relativity_childrentypes($parent); // sorted list of types
	foreach ($childrentypes as $childtype) {
		$child_display_option = variable_get('relativity_render_'. $parent->type .'_'. $childtype, 'title');
		if (strpos($child_display_option, 'view:') !== FALSE) {
			$viewname = str_replace('view:', '', $child_display_option);
			$children_box = relativity_child_as_view($parent, $childtype, $viewname);
		}
		else {
			$children_box = "\n";
			foreach ($child_nodes as $child_node) {
				if ($child_node->type != $childtype) continue;
				switch ($child_display_option) {
					case 'title':
						// $children_box .= node_get_types('name', $childtype) .': ';
						$children_box .= l(t($child_node->title), 'node/'. $child_node->nid, array('class' => 'relativity_view_'. $childtype)) ."<br />\n";
						break;
					case 'teaser':
						$children_box .= theme('fieldset', array('#title' => node_get_types('name', $childtype),
						 '#children' => node_view($child_node, TRUE)));
						break;
					case 'body':
						$children_box .= theme('fieldset', array('#title' => node_get_types('name', $childtype),
						 '#children' => node_view($child_node, FALSE)));
						break;
				}
			}
		}
		//drupal_set_message($childtype . $children_box);
		$output .= "<div class='small_header'>Related Products:</div>" . ($children_box ? "\n".'<div class="relativity_child">'. $children_box ."</div>\n" : '');
	}
	if ($output && $fieldset) {
		$output = "\n" . theme('fieldset', array('#title' => variable_get('relativity_children_label', t('Children nodes')),
			'#children' => $output));
	}
	return $output;
}

/* 
function uusm_taxonomy_treemenu_menu($ttm) {
	if ($ttm['description']) {
		$output = '<div class="taxonomy-treemenu-description">'. $ttm['description'] ."</div>\n";
	}
	$output .= '<div id="page-block-treemenu-'. check_plain($ttm['menu_name']) ."\" class=\"treemenu\">\n";
	$output .= '<div class="content clear-block">'. $ttm['body'] ."</div>\n</div>\n";
	$output .= '<div class="content clear-block">'. 'hey' ."</div>\n</div>\n";
	drupal_add_css(drupal_get_path('module', 'taxonomy_treemenu') .'/css/taxonomy-treemenu-page.css');
	return $output;
}
*/

function uusm_slideshow($element) {
	/* 
	$debugging = ($_SERVER['REMOTE_ADDR'] == variable_get('zzz_debuggers_ip', 0)) ? 1 : 0;
	if($debugging) {
		echo "<textarea cols=80 rows=30 style='font-size: 10px;'>";
		echo htmlentities(print_r($element,1));
		echo '</textarea>';
	}
	*/
	// if($element['#attributes']['id'] == 'slideshow-775') {
		$slideshow = $element['#slideshow'];
		$status = $element['#slideshow']['status'];
		$images = $element['#slideshow']['settings']['slideshow-775']['images'];
		drupal_add_js(drupal_get_path('module', 'slideshow') .'/slideshow.js');
		drupal_add_css(drupal_get_path('module', 'slideshow') .'/slideshow.css');
		drupal_add_js(array('slideshow' => $slideshow['settings']), 'setting');
		$output = (isset($element['#prefix']) ? $element['#prefix'] : '') .'<div'. drupal_attributes($element['#attributes']) .'>';
		if(isset($_GET['slide'])) { $i = $_GET['slide']; } else { $i = 0; }
		$index = $i + 1;
		if($index == count($images)) { $index = 0; }
		$output .= '<a href="/?slide=' . $index . '"><img class="image" src="'. $images[$i]['src'] .'" /></a>';
		$output .= '</div>'. (isset($element['#suffix']) ? $element['#suffix'] : '');
	// }
	return $output;
}

function uusm_service_links_node_format($links) {
	return '<div class="service-links">'. theme('links', $links) .'</div>';
}

/* UTILITY */

function uusm_block_object($m,$d) {
	$result = db_query("SELECT * FROM {blocks} WHERE module = '$m' AND delta = '$d'");
	while ($r = db_fetch_object($result)) {
		$array = module_invoke($r->module, 'block', 'view', $r->delta);
		if (isset($array) && is_array($array)) {
			foreach ($array as $k => $v) {
				$block->$k = $v;
			}
		}
	}
	return $block;
}

function uusm_get_img($fid) {
	$filepath = '';
	if($fid) {
		$sql = "SELECT filepath FROM files WHERE fid = $fid";
		$result = db_query($sql);
		while ($r = db_fetch_object($result)) {
			$filepath = $r->filepath;
		}
	}
	return $filepath;
}

function uusm_clean_filename($string) {
	$string = strtolower($string);
	$string = str_replace('&', 'and', $string);
	$string = str_replace('@', 'at', $string);
	$badchars = array('(',')',':','?','/','!','\'','"');
	$string = str_ireplace($badchars,'',$string);
	$string = preg_replace('/[^a-z0-9_-]/i', '-', $string); 
	$string = str_replace('--', '-', $string);
	return $string;
}

function uusm_humanify($string) {
	$string = str_replace('-', ' ', $string);
	$string = str_replace('_', ' ', $string);
	$string = str_replace(' and ', ' &amp; ', $string);
	$string = str_replace(' at ', ' @ ', $string);
	$string = ucwords($string);
	$string = str_replace('Uusm', 'UUSM', $string);
	$string = str_replace('Uua', 'UUA', $string);
	$string = str_replace('Uu', 'UU', $string);
	$string = str_replace('Fia', 'FIA', $string);
	$string = str_replace('Aahs', 'AAHS', $string);
	$string = str_replace('UUAndyou', 'UU & You', $string);
	return $string;
}

function uusm_searchbox() {
	$retstr = '';
	if(isset($_POST['keys'])) {
		$keysstr = $_POST['keys'];
	} else {
		$keysstr = 'search our site';
	}
	
	// $retstr .= "Search: ";
	$retstr .= "<input class='searchbox' name='keys' id='edit-keys' value='" . /*$keysstr .*/ "' style='width:350px;' onClick=\"if (this.value=='search our site') this.value = ''\">";
	return $retstr;
}

/**
 * Format a node stripe legend
 */
function uusm_calendar_stripe_legend() {
  if (empty($GLOBALS['calendar_stripes'])) {
    return '';
  }
  $header = '';/*array(
      array('class' => 'calendar-legend', 'data' => t('Item')),
      array('class' => 'calendar-legend', 'data' => t('Key'))
      );*/
  $rows = array();
  $output = '';    
  foreach ((array) $GLOBALS['calendar_stripes'] as $label => $stripe) {
    if($stripe){
      $rows[] = array('<img src="../sites/all/themes/uusm/images/legend-block.png" style="background-color:'. $stripe .';color:'. $stripe .'" class="stripe" title="Key: '. $label .'" /> '.$label);
    }
  }
  if (!empty($rows)) {
    $output .= theme_item_list($items = $rows, $title = NULL, $type = 'ul', $attributes = array('class' => 'mini calendar-legend'));
  }
  return $output;
}

/**
 * Format node stripes
 */
function uusm_calendar_stripe_stripe($node) {
  if (empty($node->stripe) || (!count($node->stripe))) {
    return;
  }
  $output = '';
  if (is_array($node->stripe_label)) {
    foreach ($node->stripe_label as $k => $stripe_label) {
      if (!empty($node->stripe[$k]) && !empty($stripe_label)) {
        $GLOBALS['calendar_stripes'][$stripe_label] = $node->stripe[$k];
        $output .= '<div class="stripe-float"><img src="../sites/all/themes/uusm/images/legend-block.png" style="background-color:'. $node->stripe[$k] .';color:'. $node->stripe[$k] .'" class="stripe" title="Key: '. $node->stripe_label[$k] .'" />&nbsp;</div>';
      }
    }
  }
  return $output;
}