<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    //中间件过滤 过滤已登录用户访问登录页面
    public function __construct()
    {
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    //返回登录视图
    public function create()
    {
        return view('sessions.create');
    }

    //处理登录请求
    public function store(Request $request)
    {
        $creadentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        //验证用户是否存在，密码是否正确
        if (Auth::attempt($creadentials,$request->has('remember'))){
            session()->flash('success','登录成功');
            $fallback = route('users.show',Auth::user());
            return redirect()->intended($fallback);
        }else{
            session()->flash('success','登录失败');
            return redirect()->back()->withInput();
        }

    }

    //退出登录
    public function destroy()
    {
        Auth::logout();
        session()->flash('success','您已成功退出！');
        return redirect('login');
    }
}
