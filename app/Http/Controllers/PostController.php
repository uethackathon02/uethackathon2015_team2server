<?php

namespace App\Http\Controllers;

use App\ClassX;
use App\Post;
use App\User;
use DateTimeZone;
use Illuminate\Http\Request;

use App\Http\Requests;
use stdClass;

class PostController extends Controller {
	/**
	 * Post
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function post( Request $request ) {
		onlyAllowPostRequest( $request );

		$all = $request->only( [
			'title',
			'content',
			'author',
			'base',
			'group',
		] );

		/**
		 * Dữ liệu trả về
		 */
		$response = new stdClass();

		/**
		 * Kiểm tra user có tồn tại hay không?
		 */
		$users = User::all()->where( 'id', intval( $all['author'] ) );
		if ( $users->count() == 0 ) {//Không tồn tại người dùng
			$response->error     = true;
			$response->error_msg = 'Đã có lỗi gì đó xảy ra!';

			return response()->json( $response );
		}

		/**
		 * Tạo post mới
		 */
		$post = Post::create( [
			'title'   => $all['title'],
			'content' => $all['content'],
			'group'   => intval( $all['group'] ),
			'author'  => intval( $all['author'] ),
			'base'    => $all['base'],
		] );

		/**
		 * Post
		 */
		$response->post  = Post::getPostInfoById( $post->id );
		$response->error = false;

		return response()->json( $response );
	}

	public function getPosts( Request $request ) {
		onlyAllowPostRequest( $request );

		$id_classX = $request->input( 'id' );
		$base      = $request->input( 'base' );

		/**
		 * Dữ liệu trả về
		 */
		$response = new stdClass();

		/**
		 * Lớp khóa học
		 */
		if ( $base == 'class_xes' ) {
			$classXes = ClassX::all()->where( 'id', intval( $id_classX ) );
			if ( $classXes->count() == 0 ) {//Không tồn tại lớp học này
				$response->error     = true;
				$response->error_msg = 'Đã có lỗi gì đó xảy ra!';

				return response()->json( $response );
			}
		}

		$postClassXes = Post::all()->where( 'base', $base )
		                    ->where( 'group', intval( $id_classX ) );
		if ( $postClassXes->count() == 0 ) {//Chưa có bài viết nào
			$response->error     = true;
			$response->error_msg = 'Chưa có bài viết nào trong lớp!';

			return response()->json( $response );
		}

		/**
		 * Danh sách các bài viết
		 */
		$arrPost = [ ];
		foreach ( $postClassXes as $index => $post ) {
			/**
			 * Post
			 */
			$p         = Post::getPostInfoById( $post->id );
			$arrPost[] = $p;
		}

		array_reverse( $arrPost );

		$response->error = false;
		$response->posts = $arrPost;

		return response()->json( $response );
	}
}
