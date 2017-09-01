<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Validator;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //        
        return view('post.index');
    }

    public function lists(){
        //
        $post = Post::all()->toArray();
        return response()->json($post);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_add_post_rules(array $data)
    {
      
      $validator = Validator::make($data, [
        'post_title' => 'required',
        'post_content' => 'required'
      ]);

      return $validator;
    }

    public function create(Request $request)
    {
        //
        $validator = $this->admin_add_post_rules($request->all());
        if($validator->fails())
        {
          return response()->json(array('error' => $validator->getMessageBag()->toArray()));
        }
        else
        {   
            $post = new Post([
              'post_title' => $request->get('post_title'),
              'post_content' => $request->get('post_content'),
              'short_description' => $request->get('short_description'),
            ]);
            $post->save();  

            $response = [
                'Post' => $post
            ];

          return $response;
        }  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $this->validate($request, [
            'post_title' => 'required|max:50',
            'post_content' => 'required'
        ]);
        $input = $request->all();
        
        $post->fill($input)->save();

        $result = [
            'Post' => $post,
        ];

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);
        $post->delete();

        return $post;
        //return redirect('/shop');
    }
}
