<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {
	/**
	 * The URIs that should be excluded from CSRF verification.
	 *
	 * @var array
	 */
	protected $except
		= [
			'v1/login',
			'v1/register',
			'v1/update',
			'v1/getGroup',
			'v1/post',
			'v1/post/comment',
			'v1/getPosts',
			'v1/timetable',
			'v1/like',
			'v1/vote',
		];
}
