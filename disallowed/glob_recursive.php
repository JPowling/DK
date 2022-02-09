/*
 * License: DWTFYW
 */

/**
 * Search recusively for files in a base directory matching a glob pattern.
 * The `GLOB_NOCHECK` flag has no effect.
 *
 * @param  string $base Directory to search
 * @param  string $pattern Glob pattern to match files
 * @param  int $flags Glob flags from https://www.php.net/manual/function.glob.php
 * @return string[] Array of files matching the pattern
 */
function glob_recursive($base, $pattern, $flags = 0) {
	$flags = $flags & ~GLOB_NOCHECK;
	
	if (substr($base, -1) !== DIRECTORY_SEPARATOR) {
		$base .= DIRECTORY_SEPARATOR;
	}

	$files = glob($base.$pattern, $flags);
	if (!is_array($files)) {
		$files = [];
	}

	$dirs = glob($base.'*', GLOB_ONLYDIR|GLOB_NOSORT|GLOB_MARK);
	if (!is_array($dirs)) {
		return $files;
	}
	
	foreach ($dirs as $dir) {
		$dirFiles = glob_recursive($dir, $pattern, $flags);
		$files = array_merge($files, $dirFiles);
	}

	return $files;
}
