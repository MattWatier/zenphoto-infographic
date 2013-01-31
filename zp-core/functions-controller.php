<?php
/**
 * Common functions used in the controller for getting/setting current classes,
 * redirecting URLs, and working with the context.
 * @package core
 */

// force UTF-8 Ø



// Determines if this request used a query string (as opposed to mod_rewrite).
// A valid encoded URL is only allowed to have one question mark: for a query string.
function is_query_request() {
	return (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '?') !== false);
}


/**
 * Returns the URL of any main page (image/album/page#/etc.)
 *
 * @parem string $special query string to add to the URL
 */
function zpurl($special='') {
	global $_zp_current_album, $_zp_current_image, $_zp_page;

	$url = '';
	if (MOD_REWRITE) {
		if (in_context(ZP_IMAGE)) {
			$encoded_suffix = implode('/', array_map('rawurlencode', explode('/', IM_SUFFIX)));
			$url = pathurlencode($_zp_current_album->name) . '/' . rawurlencode($_zp_current_image->filename) . $encoded_suffix;
		} else if (in_context(ZP_ALBUM)) {
			$url = $_zp_current_album->getAlbumLink($_zp_page);
		} else if (in_context(ZP_INDEX)) {
			$url = ($_zp_page > 1 ? 'page/' . $_zp_page : '');
		}
	} else {
		if (in_context(ZP_IMAGE)) {
			$url = 'index.php?album=' . pathurlencode($_zp_current_album->name) . '&image='. rawurlencode($_zp_current_image->filename);
		} else if (in_context(ZP_ALBUM)) {
			$url = 'index.php?album=' . pathurlencode($_zp_current_album->name) . ($_zp_page > 1 ? '&page='.$_zp_page : '');
		} else if (in_context(ZP_INDEX)) {
			$url = 'index.php' . ($_zp_page > 1 ? '?page='.$_zp_page : '');
		}
	}
	if ($url == IM_SUFFIX || empty($url)) { $url = ''; }
	if (!empty($url) && !(empty($special))) {
		if ($_zp_page > 1) {
			$url .= "&$special";
		} else {
			$url .= "?$special";
		}
	}
	return $url;
}


/**
 * Checks to see if the current URL matches the correct one, redirects to the
 * corrected URL if not with a 301 Moved Permanently.
 */
function fix_path_redirect() {
	if (MOD_REWRITE) {
		$sfx = IM_SUFFIX;
		$request_uri = urldecode($_SERVER['REQUEST_URI']);
		$i = strpos($request_uri, '?');
		if ($i !== false) {
			$params = substr($request_uri, $i+1);
			$request_uri = substr($request_uri, 0, $i);
		} else {
			$params = '';
		}
		if (strlen($sfx) > 0 && in_context(ZP_IMAGE) && substr($request_uri, -strlen($sfx)) != $sfx ) {
			$redirecturl = zpurl($params);
			header("HTTP/1.0 301 Moved Permanently");
			header("Status: 301 Moved Permanently");
			header('Location: ' . FULLWEBPATH . '/' . $redirecturl);
			exitZP();
		}
	}
}


/******************************************************************************
 ***** Action Handling and context data loading functions *********************
 ******************************************************************************/

function zp_handle_comment() {
	global $_zp_current_image, $_zp_current_album, $_zp_comment_stored, $_zp_current_zenpage_news, $_zp_current_zenpage_page;
	$activeImage = false;
	$comment_error = 0;
	$cookie = zp_getCookie('zenphoto_comment');
	if (isset($_POST['comment'])) {
		if ((in_context(ZP_ALBUM) || in_context(ZP_ZENPAGE_NEWS_ARTICLE) || in_context(ZP_ZENPAGE_PAGE))) {
			if (isset($_POST['name'])) {
				$p_name = sanitize($_POST['name'],3);
			} else {
				$p_name = NULL;
			}
			if (isset($_POST['email'])) {
				$p_email = sanitize($_POST['email'], 3);
				if (!is_valid_email_zp($p_email)) {
					$p_email = NULL;
				}
			} else {
				$p_email = NULL;
			}
			if (isset($_POST['website'])) {
				$p_website = sanitize($_POST['website'], 3);
				if (!isValidURL($p_website)) {
					$p_website = NULL;
				}
			} else {
				$p_website = NULL;
			}
			if (isset($_POST['comment'])) {
				$p_comment = sanitize($_POST['comment'], 1);
			} else {
				$p_comment = '';
			}
			$p_server = getUserIP();
			if (isset($_POST['code'])) {
				$code1 = sanitize($_POST['code'], 3);
				$code2 = sanitize($_POST['code_h'], 3);
			} else {
				$code1 = '';
				$code2 = '';
			}
			$p_private = isset($_POST['private']);
			$p_anon = isset($_POST['anon']);

			if (in_context(ZP_IMAGE) AND in_context(ZP_ALBUM)) {
				$commentobject = $_zp_current_image;
				$redirectTo = $_zp_current_image->getImageLink();
			} else if (!in_context(ZP_IMAGE) AND in_context(ZP_ALBUM)){
				$commentobject = $_zp_current_album;
				$redirectTo = $_zp_current_album->getAlbumLink();
			} else 	if (in_context(ZP_ZENPAGE_NEWS_ARTICLE)) {
				$commentobject = $_zp_current_zenpage_news;
				$redirectTo = FULLWEBPATH . '/index.php?p=news&title='.$_zp_current_zenpage_news->getTitlelink();
			} else if (in_context(ZP_ZENPAGE_PAGE)) {
				$commentobject = $_zp_current_zenpage_page;
				$redirectTo = FULLWEBPATH . '/index.php?p=pages&title='.$_zp_current_zenpage_page->getTitlelink();
			}
			$commentadded = $commentobject->addComment($p_name, $p_email, $p_website, $p_comment,
												$code1, $code2,	$p_server, $p_private, $p_anon);

			$comment_error = $commentadded->getInModeration();
			$_zp_comment_stored = array($commentadded->getName(), $commentadded->getEmail(), $commentadded->getWebsite(), $commentadded->getComment(), false,
																	$commentadded->getPrivate(), $commentadded->getAnon(), $commentadded->getCustomData());
			if (isset($_POST['remember'])) $_zp_comment_stored[4] = true;
			if (!$comment_error) {
				if (isset($_POST['remember'])) {
					// Should always re-cookie to update info in case it's changed...
					$_zp_comment_stored[3] = ''; // clear the comment itself
					zp_setCookie('zenphoto_comment', implode('|~*~|', $_zp_comment_stored), NULL, '/');
				} else {
					zp_clearCookie('zenphoto_comment', '/');
				}
				//use $redirectTo to send users back to where they came from instead of booting them back to the gallery index. (default behaviour)
				if (!isset($_SERVER['SERVER_SOFTWARE']) || strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'microsoft-iis') === false) {
					// but not for Microsoft IIS because that server fails if we redirect!
					header('Location: ' . $redirectTo);
					exitZP();
				}
			} else {
				$comment_error++;
				if ($activeImage !== false AND !in_context(ZP_ZENPAGE_NEWS_ARTICLE) AND !in_context(ZP_ZENPAGE_PAGE)) { // tricasa hack? Set the context to the image on which the comment was posted
					$_zp_current_image = $activeImage;
					$_zp_current_album = $activeImage->getAlbum();
					add_context(ZP_ALBUM | ZP_INDEX);
				}
			}
		}
		return $commentadded->comment_error_text;
	} else if (!empty($cookie)) {
		// Comment form was not submitted; get the saved info from the cookie.
		$_zp_comment_stored = explode('|~*~|', stripslashes($cookie));
		$_zp_comment_stored[4] = true;
		if (!isset($_zp_comment_stored[5])) $_zp_comment_stored[5] = false;
		if (!isset($_zp_comment_stored[6])) $_zp_comment_stored[6] = false;
		if (!isset($_zp_comment_stored[7])) $_zp_comment_stored[7] = false;
	} else {
		$_zp_comment_stored = array('','','', '', false, false, false, false);
	}
	return false;
}

/**
 * Handle AJAX editing in place
 *
 * @param string $context 	either 'image' or 'album', object to be updated
 * @param string $field		field of object to update (title, desc, etc...)
 * @param string $value		new edited value of object field
 * @since 1.3
 * @author Ozh
 **/
function zp_load_page($pagenum=NULL) {
	global $_zp_page;
	if (!is_numeric($pagenum)) {
		$_zp_page = isset($_GET['page']) ? $_GET['page'] : 1;
	} else {
		$_zp_page = round($pagenum);
	}
}


/**
 * initializes the gallery.
 */
function zp_load_gallery() {
	global 	$_zp_current_album, $_zp_current_album_restore, $_zp_albums,
					$_zp_current_image, $_zp_current_image_restore, $_zp_images, $_zp_current_comment,
					$_zp_comments, $_zp_current_context, $_zp_current_search, $_zp_current_zenpage_new,
					$_zp_current_zenpage_page, $_zp_current_category, $_zp_post_date, $_zp_pre_authorization;

	$_zp_current_album = NULL;
	$_zp_current_album_restore = NULL;
	$_zp_albums = NULL;
	$_zp_current_image = NULL;
	$_zp_current_image_restore = NULL;
	$_zp_images = NULL;
	$_zp_current_comment = NULL;
	$_zp_comments = NULL;
	$_zp_current_context = 0;
	$_zp_current_search = NULL;
	$_zp_current_zenpage_news = NULL;
	$_zp_current_zenpage_page = NULL;
	$_zp_current_category = NULL;
	$_zp_post_date = NULL;
	$_zp_pre_authorization = array();
	set_context(ZP_INDEX);
}

/**
 * Loads the search object.
 */
function zp_load_search() {
	global $_zp_current_search;
	zp_clearCookie("zenphoto_search_params");
	if (!is_object($_zp_current_search)) {
		$_zp_current_search = new SearchEngine();
	}
	add_context(ZP_SEARCH);
	$params = $_zp_current_search->getSearchParams();
	zp_setCookie("zenphoto_search_params", $params, SEARCH_DURATION);
	return $_zp_current_search;
}

/**
 * zp_load_album - loads the album given by the folder name $folder into the
 * global context, and sets the context appropriately.
 * @param $folder the folder name of the album to load. Ex: 'testalbum', 'test/subalbum', etc.
 * @param $force_cache whether to force the use of the global object cache.
 * @return the loaded album object on success, or (===false) on failure.
 */
function zp_load_album($folder, $force_nocache=false) {
	global $_zp_current_album, $_zp_gallery;
	$_zp_current_album = new Album(NULL, $folder, !$force_nocache, true);
	if (!is_object($_zp_current_album) || !$_zp_current_album->exists) return false;
	add_context(ZP_ALBUM);
	return $_zp_current_album;
}

/**
 * zp_load_image - loads the image given by the $folder and $filename into the
 * global context, and sets the context appropriately.
 * @param $folder is the folder name of the album this image is in. Ex: 'testalbum'
 * @param $filename is the filename of the image to load.
 * @return the loaded album object on success, or (===false) on failure.
 */
function zp_load_image($folder, $filename) {
	global $_zp_current_image, $_zp_current_album, $_zp_current_search;
	if (!is_object($_zp_current_album) || $_zp_current_album->name != $folder) {
		$album = zp_load_album($folder, false, true);
	} else {
		$album = $_zp_current_album;
	}
	if (!is_object($album) || !$album->exists) return false;
	$_zp_current_image = newImage($album, $filename, true);
	if (is_null($_zp_current_image) || !$_zp_current_image->exists) {
		return false;
	}
	add_context(ZP_IMAGE | ZP_ALBUM);
	return $_zp_current_image;
}

/**
 * Loads a zenpage pages page
 * Sets up $_zp_current_zenpage_page and returns it as the function result.
 * @param $titlelink the titlelink of a zenpage page to setup a page object directly. Meant to be used only for the Zenpage homepage feature.
 * @return object
 */
function zenpage_load_page() {
	global $_zp_current_zenpage_page;
	if (isset($_GET['title'])) {
		$titlelink = sanitize($_GET['title'],3);
	} else {
		$titlelink = '';
	}
	$_zp_current_zenpage_page = new ZenpagePage($titlelink);
	if ($_zp_current_zenpage_page->loaded) {
		add_context(ZP_ZENPAGE_PAGE | ZP_ZENPAGE_SINGLE);
	} else {
		$_GET['p'] = 'PAGES:'.$titlelink;
	}
	return $_zp_current_zenpage_page;
}

/**
 * Loads a zenpage news article
 * Sets up $_zp_current_zenpage_news and returns it as the function result.
 *
 * @return object
 */
function zenpage_load_news() {
	global $_zp_current_zenpage_news, $_zp_current_category, $_zp_post_date;
	if (isset($_GET['date'])) {
		add_context(ZP_ZENPAGE_NEWS_DATE);
		$_zp_post_date = sanitize($_GET['date']);
	}
	if(isset($_GET['category'])) {
		$titlelink = sanitize($_GET['category']);
		$_zp_current_category = new ZenpageCategory($titlelink);
		if ($_zp_current_category->loaded) {
			add_context(ZP_ZENPAGE_NEWS_CATEGORY);
		} else {
			$_GET['p'] = 'CATEGORY:'.$titlelink;
			unset($_GET['category']);
			return false;
		}
	}
	if (isset($_GET['title'])) {
		$titlelink = sanitize($_GET['title'],3);
		$sql = 'SELECT `id` FROM '.prefix('news').' WHERE `titlelink`='.db_quote($titlelink);
		$result = query_single_row($sql);
		if (is_array($result)) {
			add_context(ZP_ZENPAGE_NEWS_ARTICLE | ZP_ZENPAGE_SINGLE);
			$_zp_current_zenpage_news = new ZenpageNews($titlelink);
		} else {
			$_GET['p'] = 'NEWS:'.$titlelink;
		}
		return $_zp_current_zenpage_news;
	}
	return true;
}

/**
 * Figures out what is being accessed and calls the appropriate load function
 *
 * @return bool
 */
function zp_load_request() {
	if ($success = zp_apply_filter('load_request',true)) {	// filter allowed the load
		zp_load_page();
		if (isset($_GET['p'])) {
			$page = str_replace(array('/','\\','.'), '', sanitize($_GET['p']));
			if (isset($_GET['t'])) {	//	Zenphoto tiny url
				unset($_GET['t']);
				$tiny = sanitize_numeric($page);
				$asoc = getTableAsoc();
				$tbl = $tiny & 7;
				if (array_key_exists($tbl, $asoc)) {
					$tbl = $asoc[$tbl];
					$id = $tiny>>3;
					$result = query_single_row('SELECT * FROM '.prefix($tbl).' WHERE `id`='.$id);
					if ($result) {
						switch ($tbl) {
							case 'news':
							case 'pages':
								$page = $_GET['p'] = $tbl;
								$_GET['title'] = $result['titlelink'];
								break;
							case 'images':
								$image = $_GET['image'] = sanitize($result['filename']);
								$result = query_single_row('SELECT * FROM '.prefix('albums').' WHERE `id`='.$result['albumid']);
							case 'albums':
								$album = $_GET['album'] = sanitize($result['folder']);
								unset($_GET['p']);
								if (!empty($image)) {
									return zp_load_image($album, $image);
								} else if (!empty($album)) {
									return zp_load_album($album);
								}
								break;
							case 'comments':
								unset ($_GET['p']);
								$commentid = $id;
								$type = $result['type'];
								$result = query_single_row('SELECT * FROM '.prefix($result['type']).' WHERE `id`='.$result['ownerid']);
								switch ($type) {
									case 'images':
										$image = $result['filename'];
										$result = query_single_row('SELECT * FROM '.prefix('albums').' WHERE `id`='.$result['albumid']);
										$redirect = 'index.php?album='.$result['folder'].'&image='.$image;
										break;
									case 'albums':
										$album = $result['folder'];
										$redirect = 'index.php?album='.$result['folder'];
										break;
									case 'pages':
										$redirect = 'index.php?p=pages&title='.$result['titlelink'];
										break;
								}
								$redirect .= '#c_'.$commentid;
								header("HTTP/1.0 301 Moved Permanently");
								header("Status: 301 Moved Permanently");
								header('Location: ' . FULLWEBPATH . '/' . $redirect);
								exitZP();
								break;
						}
					}
				}
			}
			switch ($page) {
				case 'search':
					return zp_load_search();
					break;
				case 'pages':
					if (getOption('zp_plugin_zenpage')) {
						return zenpage_load_page();
					}
					break;
				case 'news':
					if (getOption('zp_plugin_zenpage')) {
						return zenpage_load_news();
					}
					break;
			}
		}
		//	may need image and album parameters processed
		list($album, $image) = rewrite_get_album_image('album','image');
		if (!empty($image)) {
			return zp_load_image($album, $image);
		} else if (!empty($album)) {
			return zp_load_album($album);
		}
	}
	return $success;
}

/**
*
* sets up for loading the index page
* @return string
*/
function prepareIndexPage() {
	global  $_zp_gallery_page, $_zp_script;
	handleSearchParms('index');
	$theme = setupTheme();
	$_zp_gallery_page = basename($_zp_script = THEMEFOLDER."/$theme/index.php");
	return $theme;
}

/**
 *
 * sets up for loading an album page
 */
function prepareAlbumPage() {
	global  $_zp_current_album, $_zp_gallery_page, $_zp_script;
	if ($_zp_current_album->isDynamic()) {
		$search = $_zp_current_album->getSearchEngine();
		zp_setCookie("zenphoto_search_params", $search->getSearchParams(), SEARCH_DURATION);
	} else {
		handleSearchParms('album', $_zp_current_album);
	}
	$theme =  setupTheme();
	$_zp_gallery_page = basename($_zp_script = THEMEFOLDER."/$theme/album.php");
	return $theme;
}

/**
 *
 * sets up for loading an image page
 * @return string
 */
function prepareImagePage() {
	global  $_zp_current_album, $_zp_current_image, $_zp_gallery_page, $_zp_script;
	handleSearchParms('image', $_zp_current_album, $_zp_current_image);
	$theme =  setupTheme();
	$_zp_gallery_page =  basename($_zp_script = THEMEFOLDER."/$theme/image.php");
	// re-initialize video dimensions if needed
	if (isImageVideo() & isset($_zp_flash_player)) {
		$_zp_current_image->updateDimensions();
	}
	return $theme;
}

/**
 *
 * sets up for loading p=page pages
 * @return string
 */
function prepareCustomPage() {
	global  $_zp_current_album, $_zp_current_image, $_zp_gallery_page, $_zp_script;
	handleSearchParms('page', $_zp_current_album, $_zp_current_image);
	$theme = setupTheme();
	$page = str_replace(array('/','\\','.'), '', sanitize($_GET['p']));
	if (isset($_GET['z'])) { // system page
		if ($subfolder = sanitize($_GET['z'])) {
			$subfolder .= '/';
		}
		$_zp_gallery_page = basename($_zp_script = ZENFOLDER.'/'.$subfolder.$page.'.php');
	} else {
		$_zp_script = THEMEFOLDER."/$theme/$page.php";
		$_zp_gallery_page = basename($_zp_script);
	}
	return $theme;
}

if (!getOption('license_accepted')) {
	if (isset($_GET['z']) && $_GET['z'] != 'setup') {
		// License needs agreement
		$_GET['p'] = 'license';
		$_GET['z'] = '';
	}
}
?>